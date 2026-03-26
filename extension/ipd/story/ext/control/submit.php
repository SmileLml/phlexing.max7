<?php
class myStory extends story
{
    public function submit($productID = 0, $storyType = 'story')
    {
        if($_POST)
        {
            $reviewRange = $this->post->reviewRange;
            $object      = $storyType == 'story' ? 'SRS' : 'URS';
            $product     = $this->loadModel('product')->getByID($productID);
            $programID   = $product->project ? $product->project : $this->session->project;
            $checkedItem = $reviewRange == 'all' ? '' : $this->cookie->checkedItem;
            if($reviewRange == 'selected' && !$checkedItem) die(js::alert($this->lang->story->noCheckedItem));

            die(js::locate($this->createLink('review', 'create', "program={$programID}&object=$object&productID=$productID&reviewRange=$reviewRange&checkedItem={$checkedItem}"), 'parent'));
        }

        $this->display();
    }
}
