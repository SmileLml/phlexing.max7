<?php

/**
 * Set export list value
 *
 * @access public
 * @return void
 */
public function setListValue()
{
    return $this->loadExtension('excel')->setListValue();
}

public function getOpenedBuilds($withID = true)
{
    return $this->loadExtension('excel')->getOpenedBuilds($withID);
}
