<?php
if(helper::hasFeature('deliverable'))
{
    $config->execution->form->deliverable['whenCreated'] = array('type' => 'array', 'required' => false, 'default' => array());
    $config->execution->form->deliverable['whenClosed']  = array('type' => 'array', 'required' => false, 'default' => array());
}
