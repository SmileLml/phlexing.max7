<?php
class api extends control
{
    /**
     * @param int $libID
     * @param int $version
     * @param int $release
     * @param int $moduleID
     */
    public function export($libID, $version = 0, $release = 0, $moduleID = 0)
    {
        if($release)
        {
            $rel = $this->api->getRelease(0, 'byId', $release);
            if(empty($rel)) $release = 0;
        }
        $api = $this->loadModel('doc')->getLibById($libID);
        if(empty($api)) return $this->locate($this->createLink('api', 'createLib'));

        if($_POST)
        {
            $range = $this->post->range;
            $this->post->set('module', $moduleID);
            if($range == 'productAll')
            {
                $this->post->set('productID', $api->product);
                $this->post->set('module', 0);
            }
            if($range == 'projectAll')
            {
                $this->post->set('projectID', $api->project);
                $this->post->set('module', 0);
            }

            $this->post->set('release', $release);
            $this->post->set('version', $version);
            $this->post->set('libID', $libID);
            $this->post->set('range', $range);
            $this->post->set('kind', 'api');
            $this->post->set('format', $this->post->format);
            $this->post->set('fileName', $this->post->fileName);
            return $this->fetch('file', 'doc2Word', $_POST);
        }

        $this->app->loadLang('file');

        $this->view->title    = $this->lang->export;
        $this->view->fileName = zget($api, 'name', '');
        $this->view->data     = $api;
        $this->view->libID    = $libID;
        $this->view->version  = $version;
        $this->view->release  = $release;

        $this->view->chapters = array();
        $this->view->chapters['listAll'] = $this->lang->api->exportListAll;
        if(empty($release) and empty($version) and $this->session->spaceType == 'api')
        {
            if($api->product)
            {
                $this->view->chapters['productAll'] = $this->lang->api->exportProductAll;
            }
            elseif($api->project)
            {
                $this->view->chapters['projectAll'] = $this->lang->api->exportProjectAll;
            }
            else
            {
                $this->view->chapters['noLinkAll'] = $this->lang->api->exportNoLinkAll;
            }
        }

        $this->display();
    }
}
