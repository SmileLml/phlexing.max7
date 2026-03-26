<?php
class myDoc extends doc
{
    /**
     * 获取项目关联的产品。
     * Ajax get linked products of project.
     *
     * @param  string $projectIdList
     * @access public
     * @return void
     */
    public function ajaxGetLinkedProducts($projectIdList)
    {
        $linkedPrducts = $this->loadModel('product')->getProductPairsByProject(explode(',', $projectIdList), 'all', '', true, true);
        $productItems  = array();
        foreach($linkedPrducts as $productID => $productName)
        {
            if(empty($productID)) continue;
            $productItems[] = array('text' => $productName, 'value' => $productID);
        }
        return print(json_encode($productItems));
    }
}
