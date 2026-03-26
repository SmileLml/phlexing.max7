<?php
class myProgram extends program
{
    /**
     * 以产品视角查看项目集。
     * Show program list in product view.
     * copied from all() function of product module.
     *
     * @param  string  $browseType
     * @param  string  $orderBy
     * @param  int     $param
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @access public
     * @return void
     */
    public function productView($browseType = 'unclosed', $orderBy = 'program_asc', $param = 0, $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $this->config->program->search['params']['charter']['values'] = $this->loadModel('charter')->getPairs('all');

        return parent::productView($browseType, $orderBy, $param, $recTotal, $recPerPage, $pageID);
    }
}
