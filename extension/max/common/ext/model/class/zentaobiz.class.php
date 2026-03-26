<?php
class zentaobizCommon extends commonModel
{
    public function setCompany()
    {
        if(function_exists('ioncube_license_properties') && !isset($_SESSION['bizIoncubeProperties']) && empty($this->app->upgrading) && empty($this->app->installing))
        {
            $properties = ioncube_license_properties();

            if($properties)
            {
                $ioncubeProperties = new stdclass();
                foreach($properties as $key => $property) $ioncubeProperties->$key = $property['value'];

                $user = $this->dao->select("COUNT('*') as count")->from(TABLE_USER)
                    ->where('deleted')->eq(0)
                    ->andWhere('visions')->like("%{$this->config->vision}%")
                    ->fetch();
                if(isset($properties['user']) && $properties['user']['value'] < $user->count) $ioncubeProperties->userLimited = true;
                $this->session->set('bizIoncubeProperties', $ioncubeProperties);
            }
        }
    }

    /**
     * 检查已授权的插件。
     * Check extension license.
     *
     * @param  string $ext  safe|devops
     * @param  string $afterDate
     * @access public
     * @return bool
     * @param string $checkDate
     */
    public function checkExtLicense($ext, $checkDate = '')
    {
        if(function_exists('ioncube_license_properties'))
        {
            $properties = ioncube_license_properties();
            if($properties)
            {
                $licensePath = $this->app->configRoot . 'license' . DS . 'zentao.txt';
                if($checkDate && !file_exists($licensePath)) return true;
                if($checkDate && file_exists($licensePath) && filectime($licensePath) < strtotime($checkDate)) return true;

                $this->loadModel('admin');
                $licenseMethod = 'get' . ucfirst($ext) . 'License';
                if(!method_exists($this->admin, $licenseMethod)) return false;

                $properties = $this->admin->$licenseMethod();
                if(!$properties['expireDate'] || $properties['expireDate'] >= date('Y-m-d')) return true;
                return false;
            }
        }

        return true;
    }

    /**
     * 禅道鉴权核心方法，如果用户没有当前模块、方法的权限，则跳转到登录页面或者拒绝页面。
     * Check the user has permission to access this method, if not, locate to the login page or deny page.
     *
     * @access public
     * @return void
     */
    public function checkPriv()
    {
        $module = $this->app->getModuleName();
        $method = $this->app->getMethodName();

        $this->app->loadConfig('misc');
        $hasDevopsExt = $this->checkExtLicense('devops', zget($this->config->misc, 'featureLimit', ''));
        if(!$hasDevopsExt)
        {
            if(isset($this->lang->devops->homeMenu->artifactrepo))             unset($this->lang->devops->homeMenu->artifactrepo);
            if(isset($this->lang->scrum->menu->devops['subMenu']->review))     unset($this->lang->scrum->menu->devops['subMenu']->review);
            if(isset($this->lang->waterfall->menu->devops['subMenu']->review)) unset($this->lang->waterfall->menu->devops['subMenu']->review);
            if(isset($this->lang->execution->menu->devops['subMenu']->review)) unset($this->lang->execution->menu->devops['subMenu']->review);
            if(isset($this->lang->devops->menu->review))                       unset($this->lang->devops->menu->review);

            if($module == 'gitfox' || $module == 'artifactrepo' || ($module == 'repo' && $method == 'review')) $this->deny($module, $method);
        }

        parent::checkPriv();
    }
}
