<?php
/**
 * The control file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件) 
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     project 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
if(!defined('TABLE_RELATIONOFTASKS')) define('TABLE_RELATIONOFTASKS', '`' . $config->db->prefix . 'relationoftasks`');

$filter->project->gantt = new stdclass();
$filter->project->gantt->cookie['ganttType'] = 'code';
