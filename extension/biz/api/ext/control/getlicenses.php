<?php
class api extends control
{
    /**
     * Get licenses.
     * 
     * @access public
     * @return void
     */
    public function getLicenses()
    {
        $ioncubeProperties = $this->api->getLicenses();
        session_unset(); 
        $_SESSION = array();

        die(json_encode($ioncubeProperties));
    }
}
