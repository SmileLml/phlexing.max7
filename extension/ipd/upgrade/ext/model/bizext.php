<?php
/**
 * Record finished task effort.
 * 
 * @access public
 * @return bool
 */
public function recordFinished()
{
    return $this->loadExtension('bizext')->recordFinished();
}

/**
 * Fix repo prefix.
 * 
 * @access public
 * @return void
 */
public function fixRepo()
{
    return $this->loadExtension('bizext')->fixRepo();
}

/**
 * Fix report for add unique key.
 * 
 * @access public
 * @return bool
 */
public function fixReport()
{
    return $this->loadExtension('bizext')->fixReport();
}

public function fixReportLang()
{
    return $this->loadExtension('bizext')->fixReportLang();
}

public function checkURAndSR()
{
    return $this->loadExtension('bizext')->checkURAndSR();
}

