<?php
helper::importControl('convert');
class myConvert extends convert
{

    /**
     * 数据导入首页。
     * Index page of convert.
     *
     * @param  string $mode
     * @access public
     * @return void
     */
    public function index($mode = '')
    {
        $confluenceRelation = $this->session->confluenceRelation;
        $confluenceRelation = $confluenceRelation ? json_decode($confluenceRelation, true) : array();
        if($confluenceRelation && $mode == 'restore')
        {
            $confirmedURL = inlink('mapConfluence2Zentao');
            $canceledURL  = inlink('index', 'type=reset');
            $this->send(array('result' => 'success', 'load' => array('confirm' => $this->lang->convert->jira->restore, 'confirmed' => $confirmedURL, 'canceled' => $canceledURL)));
        }
        if($mode == 'reset')
        {
            unset($_SESSION['confluenceRelation']);
            unset($_SESSION['confluenceUser']);
            unset($_SESSION['confluenceUsers']);
        }
        parent::index($mode);
    }
}
