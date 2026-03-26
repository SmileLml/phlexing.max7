<?php
class cmmiUser extends userModel
{
    /**
     * Get user pairs by role.
     *
     * @param  string    $role
     * @access public
     * @return array
     */
    public function getPairsByRole($role)
    {
        return $this->dao->select('account', 'realname')->from(TABLE_USER)->where('role')->eq($role)->fetchPairs();
    }
}
