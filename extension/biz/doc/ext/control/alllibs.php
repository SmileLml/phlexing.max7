<?php
helper::importControl('doc');
class mydoc extends doc
{
    public function allLibs($type = 'custom', $product = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(!empty($this->app->user->feedback) or $this->cookie->feedbackView) $type = 'custom';
        return parent::allLibs($type, $product, $recTotal, $recPerPage, $pageID);
    }
}
