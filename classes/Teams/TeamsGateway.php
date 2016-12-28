<?php


namespace phpCollab\Teams;

use phpCollab\Database;

class TeamsGateway
{
    protected $db;
    protected $initrequest;

    /**
     * TeamsGateway constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->initrequest = $GLOBALS['initrequest'];
    }

    public function getTeamByProjectIdAndTeamMember($projectId, $memberId)
    {
        $whereStatement = " WHERE tea.project = :project_id AND tea.member = :member_id";
        $this->db->query($this->initrequest["teams"] . $whereStatement);
        $this->db->bind(':project_id', $projectId);
        $this->db->bind(':member_id', $memberId);
        $results = $this->db->resultset();
        return $results;
    }

    public function getTeamByProjectIdAndOrderBy($projectId, $orderBy)
    {
        $whereStatement = " WHERE tea.project = :project_id";

        if (isset($orderBy)) {
            $orderByStatement = " ORDER BY :order_by";
        } else {
            $orderByStatement = '';
        }
        $sql = $this->initrequest["teams"] . $whereStatement . $orderByStatement;

        $this->db->query($sql);
        $this->db->bind(':project_id', $projectId);
        if (isset($orderBy)) {
            $this->db->bind(':order_by', $orderBy);
        }
        $results = $this->db->resultset();

        return $results;
    }
}
