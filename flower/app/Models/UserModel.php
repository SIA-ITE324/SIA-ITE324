<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?array
    {
        $user = $this->where('email', $email)->first();
        return $user ?: null;
    }

    /**
     * Verify password and return user if valid
     */
    public function verifyLogin(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }
        return $user;
    }
}
