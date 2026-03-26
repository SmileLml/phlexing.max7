<?php
class myProgram extends program
{
    /**
     * Program list.
     *
     * @param  string  $status
     * @param  string  $orderBy
     * @access public
     * @return void
     * @param int $recTotal
     * @param int $recPerPage
     * @param int $pageID
     * @param int $param
     */
    public function browse($status = 'unclosed', $orderBy = 'order_asc', $recTotal = 0, $recPerPage = 100, $pageID = 1, $param = 0)
    {
        $this->config->program->search['params']['charter']['values'] = $this->loadModel('charter')->getPairs('all');

        return parent::browse($status, $orderBy, $recTotal, $recPerPage, $pageID, $param);
    }
}
