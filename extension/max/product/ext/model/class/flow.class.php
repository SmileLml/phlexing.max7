<?php
class flowProduct extends productModel
{
    /**
     * @param string $module
     * @param string $method
     * @param string $extra
     * @param bool $branch
     */
    public function getProductLink($module, $method, $extra = '', $branch = false)
    {
        $link = parent::getProductLink($module, $method, $extra, $branch);

        if($module == 'flow' and !empty($extra))
        {
            /* Get flow by extra. */
            $flow = $this->dao->select('*')->from(TABLE_WORKFLOW)->where('id')->eq((int)$extra)->fetch();
            if($flow)
            {
                $labels = $this->dao->select('*')->from(TABLE_WORKFLOWLABEL)->where('module')->eq($flow->module)->orderBy('order')->fetchAll();
                foreach($labels as $label)
                {
                    if($label->buildin) continue;
                    if(!commonModel::hasPriv($flow->module, $label->id)) continue;

                    $link = helper::createLink($flow->module, 'browse', "mode=browse&label={$label->id}");
                    break;
                }
            }
        }

        return $link;
    }
}
