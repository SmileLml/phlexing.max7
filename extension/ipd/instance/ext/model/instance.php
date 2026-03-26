<?php
/**
 * @param object $instance
 * @param string $action
 */
public function isClickable($instance, $action)
{
    return $this->loadExtension('instance')->isClickable($instance, $action);
}

/**
 * @param object $customData
 * @param object $dbInfo
 * @param object $instance
 */
public function installationSettingsMap($customData, $dbInfo, $instance)
{
    return $this->loadExtension('instance')->installationSettingsMap($customData, $dbInfo, $instance);
}

/**
 * @return bool|object
 * @param object $instance
 */
public function checkAccessForWS($instance)
{
    return $this->loadExtension('instance')->checkAccessForWS($instance);
}

/**
 * @return bool|object
 */
public function getZenTaoApp()
{
    return $this->loadExtension('instance')->getZenTaoApp();
}

/**
 * @return bool|object
 * @param object $instance
 */
public function stop($instance)
{
    return $this->loadExtension('instance')->stop($instance);
}

public function deleteZenTaoApp()
{
    return $this->loadExtension('instance')->deleteZenTaoApp();
}

/**
 * @param object $query
 */
public function getOptionsByApi($query)
{
    return $this->loadExtension('instance')->getOptionsByApi($query);
}
