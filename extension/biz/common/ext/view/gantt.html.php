<?php
/**
 * The view file of fullcalendar module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     fullcalendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
css::import($jsRoot . 'dhtmlxgantt/min.css');
js::import($jsRoot . 'dhtmlxgantt/min.js');
js::import($jsRoot . 'dhtmlxgantt/ext/dhtmlxgantt_critical_path.js');
js::import($jsRoot . 'dhtmlxgantt/ext/dhtmlxgantt_fullscreen.js');
js::import($jsRoot . 'dhtmlxgantt/ext/dhtmlxgantt_smart_rendering.js');
js::import($jsRoot . 'dhtmlxgantt/ext/dhtmlxgantt_marker.js');
$currentLang = $app->getClientLang();
if($currentLang != 'en') js::import($jsRoot . 'dhtmlxgantt/lang/' . $currentLang . '.js');
?>
