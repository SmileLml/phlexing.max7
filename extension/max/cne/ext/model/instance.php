<?php
/**
 * @param object $instance
 */
public function getCustomFields($instance)
{
    return $this->loadExtension('instance')->getCustomFields($instance);
}
