<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'action', 'entity_type', 'entity_id', 'description', 'ip_address', 'user_agent', 'created_at'
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    /**
     * Log an activity
     */
    public function logActivity($userId, $action, $entityType, $entityId, $description, $ipAddress = null, $userAgent = null)
    {
        $data = [
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'ip_address' => $ipAddress ?? $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }

    /**
     * Get timeline for a specific order
     */
    public function getOrderTimeline($orderId)
    {
        return $this->select('activity_logs.*, users.name as user_name, users.email as user_email')
                    ->join('users', 'users.id = activity_logs.user_id', 'left')
                    ->where('activity_logs.entity_type', 'order')
                    ->where('activity_logs.entity_id', $orderId)
                    ->orderBy('activity_logs.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get recent activities for dashboard
     */
    public function getRecentActivities($limit = 10)
    {
        return $this->select('activity_logs.*, users.name as user_name')
                    ->join('users', 'users.id = activity_logs.user_id', 'left')
                    ->orderBy('activity_logs.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get activities by user
     */
    public function getUserActivities($userId, $limit = 50)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get activities by date range
     */
    public function getActivitiesByDateRange($dateFrom, $dateTo, $entityType = null)
    {
        $builder = $this->where('created_at >=', $dateFrom)
                       ->where('created_at <=', $dateTo);

        if ($entityType) {
            $builder->where('entity_type', $entityType);
        }

        return $builder->orderBy('created_at', 'DESC')
                      ->findAll();
    }

    /**
     * Get activity statistics
     */
    public function getActivityStats($dateFrom = null, $dateTo = null)
    {
        $builder = $this->select('action, COUNT(*) as count')
                       ->groupBy('action')
                       ->orderBy('count', 'DESC');

        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('created_at <=', $dateTo);
        }

        return $builder->findAll();
    }

    /**
     * Clean old activities (for maintenance)
     */
    public function cleanOldActivities($daysOld = 90)
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$daysOld} days"));
        
        return $this->where('created_at <', $cutoffDate)
                    ->delete();
    }
}
