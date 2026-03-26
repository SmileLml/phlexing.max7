<?php
class myTestcase extends testcase
{
    public function submit($productID = 0)
    {
        if($_POST)
        {
            $reviewRange = $this->post->range;
            $object      = $this->post->object;
            $product     = $this->loadModel('product')->getByID($productID);
            $programID   = !empty($product->project) ? $product->project : $this->session->project;
            $checkedItem = $reviewRange == 'all' ? '' : $this->cookie->checkedItem;

            $caseIdList = array();
            if($checkedItem)
            {
                foreach(explode(',', $checkedItem) as $item)
                {
                    if(strpos($item, 'case_') !== false) $caseIdList[] = str_replace('case_', '', $item);
                }
            }
            $caseIdList = implode(',', $caseIdList);

            die(js::locate($this->createLink('review', 'create', "program={$programID}&object=$object&productID=$productID&reviewRange=$reviewRange&checkedItem={$caseIdList}"), 'parent.parent'));
        }

        $this->display();
    }
}
