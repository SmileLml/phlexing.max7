<?php
helper::importControl('convert');
class myConvert extends convert
{
    /**
     * Confluence数据导入提示。
     * Import confluence notice.
     *
     * @access public
     * @return void
     */
    public function importConfluenceNotice()
    {
        if($this->server->request_method == 'POST')
        {
            $domain = $this->post->confluenceDomain;
            if(strpos($domain, 'http') === false) $domain = 'http://' . $domain;
            if(strpos($domain, 'atlassian.net') !== false && strpos($domain, '/wiki') === false) $domain = $domain . '/wiki';

            $confluenceApi = array();
            $confluenceApi['domain'] = trim($domain, '/');
            $confluenceApi['admin']  = $this->post->confluenceAdmin;
            $confluenceApi['token']  = $this->post->confluenceToken;
            $this->session->set('confluenceApi', json_encode($confluenceApi));
            $this->convert->checkConfluenceApi();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $link = $this->createLink('convert', 'initConfluenceUser');
            return $this->send(array('result' => 'success', 'load' => $link));
        }

        $this->view->title         = $this->lang->convert->confluence->notice;
        $this->view->confluenceApi = !empty($_SESSION['confluenceApi']) ? json_decode($this->session->confluenceApi) : array();
        $this->display();
    }
}
