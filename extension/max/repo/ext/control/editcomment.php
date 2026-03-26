<?php
helper::importControl('repo');
class myRepo extends repo
{
    /**
     * 编辑评论。
     * Edit comment.
     *
     * @param  int    $commentID
     * @access public
     * @return void
     */
    public function editComment($commentID)
    {
        if(!empty($_POST))
        {
            $comment = $this->loadModel('file')->pasteImage($this->post->commentText);
            $this->repo->updateComment($commentID, $comment);
            return $this->sendSuccess(array('message' => '', 'load' => $this->post->loadPage ? $this->post->loadPage : true));
        }
    }
}
