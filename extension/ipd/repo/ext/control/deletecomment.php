<?php
helper::importControl('repo');;
class myRepo extends repo
{
    /**
     * Delete comment.
     *
     * @param  int    $commentID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteComment($commentID, $confirm = 'no')
    {
        if($confirm == 'yes')
        {
            $result = $this->repo->deleteComment($commentID);
            if($result) echo 'deleted';
        }
        return false;
    }
}
