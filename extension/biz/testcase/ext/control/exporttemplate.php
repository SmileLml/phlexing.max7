<?php
helper::importControl('testcase');
class mytestcase extends testcase
{
    public function exportTemplate($productID)
    {
        $product = $this->loadModel('product')->getByID($productID);

        if($product->type == 'normal') $this->config->testcase->templateFields = str_replace('branch,', '', $this->config->testcase->templateFields);
        if($product->shadow and $this->app->tab == 'project') $this->config->testcase->templateFields = str_replace('product,', '', $this->config->testcase->templateFields);

        if($_POST)
        {
            $product = $this->loadModel('product')->getByID($productID);
            if($product->type != 'normal') $this->config->testcase->templateFields = str_replace('module,', 'branch,module,', $this->config->testcase->templateFields);

            $this->post->set('product', $product->name);
            $this->testcase->setListValue($productID);
            $this->post->set('resultListList', join(',', $this->lang->testcase->resultList));
            $this->fetch('transfer', 'exportTemplate', 'model=testcase&params=productID='. $productID);
        }
        $this->loadModel('transfer');

        $this->view->productID = $productID;
        $this->display();
    }
}
