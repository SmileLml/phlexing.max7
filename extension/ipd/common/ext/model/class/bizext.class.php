<?php
class bizextCommon extends commonModel
{
    public function setCompany()
    {
        if(function_exists('ioncube_license_properties') and !isset($_SESSION['ioncubeProperties']))
        {
            $properties = ioncube_license_properties();
            if($properties)
            {
                $ioncubeProperties = new stdclass();
                foreach($properties as $key => $property) $ioncubeProperties->$key = $property['value'];

                $user = $this->dao->select("COUNT('*') as count")->from(TABLE_USER)->where('deleted')->eq(0)->fetch();
                if(isset($properties['user']) and $properties['user']['value'] < $user->count) $ioncubeProperties->userLimited = true;
                $this->session->set('ioncubeProperties', $ioncubeProperties);
            }
        }

        $httpHost = $this->server->http_host;

        if($this->session->company)
        {
            $this->app->company = $this->session->company;
        }
        else
        {
            $company = $this->loadModel('company')->getFirst();
            if(!$company) $this->app->triggerError(sprintf($this->lang->error->companyNotFound, $httpHost), __FILE__, __LINE__, $exit = true);
            $this->session->set('company', $company);
            $this->app->company  = $company;
        }
    }
}
