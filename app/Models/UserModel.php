<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'role',
        'status',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'email_verified',
        'email_verification_token',
        'password_reset_token',
        'password_reset_expires',
        'last_login'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'phone' => 'permit_empty|max_length[20]',
        'role' => 'required|in_list[admin,staff,customer]',
        'status' => 'required|in_list[active,inactive,suspended]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email address is already registered.',
            'valid_email' => 'Please enter a valid email address.',
        ],
        'password' => [
            'min_length' => 'Password must be at least 8 characters long.',
        ],
        'role' => [
            'in_list' => 'Invalid user role specified.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Hash password before saving
     */
    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

        return $data;
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Verify user password
     */
    public function verifyPassword($email, $password)
    {
        $user = $this->getUserByEmail($email);
        
        if (!$user) {
            return false;
        }

        return password_verify($password, $user['password']);
    }

    /**
     * Update last login time
     */
    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => Time::now()]);
    }

    /**
     * Generate password reset token
     */
    public function generatePasswordResetToken($email)
    {
        $token = bin2hex(random_bytes(32));
        $expires = Time::now()->addHours(1);

        $this->where('email', $email)->set([
            'password_reset_token' => $token,
            'password_reset_expires' => $expires
        ])->update();

        return $token;
    }

    /**
     * Verify password reset token
     */
    public function verifyPasswordResetToken($token)
    {
        $user = $this->where('password_reset_token', $token)->first();

        if (!$user) {
            return false;
        }

        $expires = new Time($user['password_reset_expires']);
        
        if (Time::now()->isAfter($expires)) {
            // Token expired, clear it
            $this->update($user['id'], [
                'password_reset_token' => null,
                'password_reset_expires' => null
            ]);
            return false;
        }

        return $user;
    }

    /**
     * Clear password reset token
     */
    public function clearPasswordResetToken($userId)
    {
        return $this->update($userId, [
            'password_reset_token' => null,
            'password_reset_expires' => null
        ]);
    }

    /**
     * Get active users by role
     */
    public function getActiveUsersByRole($role)
    {
        return $this->where('role', $role)
                   ->where('status', 'active')
                   ->orderBy('first_name', 'ASC')
                   ->findAll();
    }

    /**
     * Get customers with order statistics
     */
    public function getCustomersWithStats()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT u.*, 
                   COUNT(o.id) as total_orders,
                   COALESCE(SUM(o.total_amount), 0) as total_spent,
                   MAX(o.created_at) as last_order_date
            FROM users u
            LEFT JOIN orders o ON u.id = o.customer_id
            WHERE u.role = 'customer'
            GROUP BY u.id
            ORDER BY u.created_at DESC
        ");

        return $query->getResultArray();
    }

    /**
     * Search users
     */
    public function searchUsers($searchTerm, $role = null, $status = null)
    {
        $builder = $this->builder();

        if ($searchTerm) {
            $builder->groupStart()
                    ->like('first_name', $searchTerm)
                    ->orLike('last_name', $searchTerm)
                    ->orLike('email', $searchTerm)
                    ->orLike('phone', $searchTerm)
                    ->groupEnd();
        }

        if ($role) {
            $builder->where('role', $role);
        }

        if ($status) {
            $builder->where('status', $status);
        }

        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Get user statistics
     */
    public function getUserStats()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                role,
                status,
                COUNT(*) as count,
                DATE(created_at) as date
            FROM users
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY role, status, DATE(created_at)
            ORDER BY date DESC
        ");

        return $query->getResultArray();
    }
}
