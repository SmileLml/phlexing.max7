<?php
class zflowGroup extends groupModel
{
    /**
     * Update accounts.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function updateAccounts($groupID)
    {
        $groupUsers    = $this->getUserPairs($groupID);
        $groupAccounts = array_keys($groupUsers);
        $groupAccounts = implode(',', array_keys($groupUsers));

        if(!empty($this->config->group->unUpdatedAccounts))
        {
            $groupAccounts = trim($this->config->group->unUpdatedAccounts, ',') . ',' . $groupAccounts;
            $groupAccounts = explode(',', $groupAccounts);
            $groupAccounts = array_unique($groupAccounts);
            $groupAccounts = implode(',', $groupAccounts);
        }

        $this->loadModel('setting')->setItem("system.group.unUpdatedAccounts", ',' . trim($groupAccounts, ',') . ',');
    }
}
