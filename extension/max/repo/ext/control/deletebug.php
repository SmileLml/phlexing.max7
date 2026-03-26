<?php
helper::importControl('repo');;
class myRepo extends repo
{
    /**
     * Delete bug.
     *
     * @param  int    $bugID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteBug($bugID, $confirm = 'no')
    {
        if($confirm == 'yes')
        {
            $this->loadModel('bug')->delete(TABLE_BUG, $bugID);
            echo 'deleted';
        }
        return false;
    }
}
