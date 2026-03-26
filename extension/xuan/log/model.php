<?php
class logModel extends model
{
    /**
     * get action records
     * @access public
     * @param string @orderBy
     * @param string @objectType
     * @param string @search
     * @param string @date
     * @param object $pager
     * @param bool   $onlyAdmins 仅返回系统管理员和安全管理员操作所产生的日志
     * @return array
     */
    public function getActionRecords($orderBy = 'date_desc', $objectType = 'all', $search = '', $date = '', $pager = null, $onlyAdmins = false)
    {
        $adminsAccountList = $onlyAdmins ? $this->loadModel('group')->getAdminsAccountList() : array();

        return $this->dao->select('*')->from(TABLE_ACTION)
            ->where(1)

            ->beginIF($search)
            ->andWhere('actor')->like("%$search%")
            ->fi()

            ->beginIF($date)
            ->andWhere('date')->like("$date%")
            ->fi()

            ->beginIF($objectType != 'all')
            ->andWhere('objectType')->eq($objectType)
            ->fi()

            ->beginIF($onlyAdmins)
            ->andWhere('actor')->in($adminsAccountList)
            ->fi()

            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }
}
