<?php
class webProduct extends productModel
{
    /**
     * @param string|int $branch
     * @param int $productID
     * @param string $extra
     */
    public function setMenu($productID = 0, $branch = '', $extra = '')
    {
        $result = parent::setMenu($productID, $branch, $extra);
        if($this->app->viewType == 'mhtml')
        {
            $this->lang->product->menu->all = "{$this->lang->product->all}|product|all|";
        }
        return $result;
    }
}
