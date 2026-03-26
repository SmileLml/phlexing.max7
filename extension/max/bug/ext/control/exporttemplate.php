<?php
helper::importControl('bug');
class mybug extends bug
{
    /**
     * @param int|string $branch
     * @param int $productID
     */
    public function exportTemplate($productID, $branch = 0)
    {
        $product = $this->loadModel('product')->getByID($productID);

        if($product->type == 'normal') $this->config->bug->templateFields = str_replace('branch,', '', $this->config->bug->templateFields);
        if($product->type != 'normal') $this->config->bug->listFields .= ',branch';
        if($_POST)
        {
            $product = $this->loadModel('product')->getByID($productID);
            $this->post->set('product', $product->name);
            $this->session->set('bugTransferParams', array('branch' => $branch, 'productID' => $productID));
            $this->config->bug->dtable->fieldList['branch']['dataSource']['params']['params']    = 'active';
            $this->config->bug->dtable->fieldList['branch']['dataSource']['params']['productID'] = $productID;

            $this->config->bug->dtable->fieldList['project']['dataSource']   = array('module' => 'product', 'method' => 'getProjectPairsByProduct', 'params' => array('productID' => $productID, 'branch' => (string)$branch));
            $this->config->bug->dtable->fieldList['execution']['dataSource'] = array('module' => 'product', 'method' => 'getExecutionPairsByProduct', 'params' => array('productID' => $productID, 'branch' => (string)$branch));
            $this->config->bug->dtable->fieldList['story']['dataSource']     = array('module' => 'story',   'method' =>'getProductStoryPairs', 'params' => ['productIdList' => (int)$productID, 'branch' => 'all', 'moduleIdList' => '', 'status' => 'all', 'order' => 'id_desc', 'limit' => 0, 'type' => 'story', 'storyType' => 'story']);

            $this->fetch('transfer', 'exportTemplate', 'model=bug');
        }

        $this->loadModel('transfer');
        $this->display();
    }
}
