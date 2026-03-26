<?php
class myInstall extends install
{
    /**
     * The index page of install.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        if(strpos($this->config->version, 'pro') === 0) $this->view->versionName = $this->lang->zentaoPMS . str_replace('pro', $this->lang->proName . ' ', $this->config->version);
        if(strpos($this->config->version, 'biz') === 0) $this->view->versionName = $this->lang->zentaoPMS . str_replace('biz', $this->lang->bizName . ' ', $this->config->version);
        if(strpos($this->config->version, 'max') === 0) $this->view->versionName = str_replace('max', $this->lang->maxName . ' ', $this->config->version);
        parent::index();
    }
}
