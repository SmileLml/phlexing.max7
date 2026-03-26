<?php
helper::importControl('group');
class myGroup extends group
{
    /**
     * 分配分组权限。
     * Manage privleges of a group.
     *
     * @param  string $type     byPackage|byGroup|byModule
     * @param  int    $param
     * @param  string $nav
     * @param  string $version
     * @access public
     * @return void
     */
    public function managePriv($type = 'byPackage', $param = 0, $nav = '', $version = '')
    {
        $this->app->loadLang('misc');
        if(!$this->loadModel('common')->checkExtLicense('devops', zget($this->lang->misc->releaseDate, '20.1.1', '')))
        {
            if(isset($this->lang->resource->artifactrepo))        unset($this->lang->resource->artifactrepo);
            if(isset($this->lang->resource->repo->review))        unset($this->lang->resource->repo->review);
            if(isset($this->lang->resource->repo->addBug))        unset($this->lang->resource->repo->addBug);
            if(isset($this->lang->resource->repo->editBug))       unset($this->lang->resource->repo->editBug);
            if(isset($this->lang->resource->repo->deleteBug))     unset($this->lang->resource->repo->deleteBug);
            if(isset($this->lang->resource->repo->addComment))    unset($this->lang->resource->repo->addComment);
            if(isset($this->lang->resource->repo->editComment))   unset($this->lang->resource->repo->editComment);
            if(isset($this->lang->resource->repo->deleteComment)) unset($this->lang->resource->repo->deleteComment);

            if(isset($this->lang->group->package->artifactrepo))       unset($this->lang->group->package->artifactrepo);
            if(isset($this->lang->group->package->deleteArtifactrepo)) unset($this->lang->group->package->deleteArtifactrepo);
            if(isset($this->lang->group->package->manageArtifactrepo)) unset($this->lang->group->package->manageArtifactrepo);
            if(isset($this->lang->group->package->browseArtifactrepo)) unset($this->lang->group->package->browseArtifactrepo);
        }

        parent::managePriv($type, $param, $nav, $version);
    }
}
