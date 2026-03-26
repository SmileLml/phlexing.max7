<?php
class bizextApi extends control
{ 
    public function getLicenses()
    {
        $ioncubeProperties = array();

        if(function_exists('ioncube_license_properties'))
        {
            $properties = ioncube_license_properties();

            if($properties)
            {
                foreach($properties as $key => $property) $ioncubeProperties[$key] = $property['value'];
            }
        }

        return $ioncubeProperties;
    }
}
