<?php
helper::importControl('repo');;
class myRepo extends repo
{
    /**
     * 添加评论。
     * Add comment.
     *
     * @access public
     * @return void
     */
    public function addComment()
    {
        if(!empty($_POST))
        {
            $now  = helper::now();
            $bug  = $this->loadModel('bug')->getByID($this->post->objectID);
            $data = fixer::input('post')
                ->add('objectType', 'bug')
                ->add('product', ',' . $bug->product . ',')
                ->add('project', $bug->project)
                ->add('actor', $this->app->user->account)
                ->add('action', 'commented')
                ->add('date', $now)
                ->remove('loadPage')
                ->get();

            $this->dao->insert(TABLE_ACTION)->data($data)->exec();
            return $this->sendSuccess(array('message' => '', 'load' => $this->post->loadPage ? $this->post->loadPage : true));
        }
    }
}
