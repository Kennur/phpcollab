<?php


namespace phpCollab\Notifications;

use phpCollab\Database;

/**
 * Class Notifications
 * @package phpCollab\Notifications
 */
class Notifications
{
    protected $notifications_gateway;
    protected $db;

    /**
     * Members constructor.
     */
    public function __construct()
    {
        $this->db = new Database();

        $this->notifications_gateway = new NotificationsGateway($this->db);
    }

    /**
     * @param $memberId
     * @return mixed
     */
    public function getNotificationsWhereMemeberIn($memberId)
    {
        return $this->notifications_gateway->getNotificationsWhereMemeberIn($memberId);
    }

    /**
     * @param $memberId
     */
    public function deleteNotificationsByMemberIdIn($memberId)
    {
        $this->notifications_gateway->deleteNotificationsByMemberIdIn($memberId);
    }

}
