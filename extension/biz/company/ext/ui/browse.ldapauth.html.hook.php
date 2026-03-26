<?php
namespace zin;
global $lang, $config;
//$this->session->set('ldapBackLink', $this->app->getURI(true));

if(hasPriv('user', 'importLDAP') && $config->vision != 'or')
{
    $deptTree   = data('deptTree');
    $type       = data('type');
    $param      = data('param');
    $browseType = data('browseType');
    query('#mainContent .sidebar')->replaceWith(
        sidebar
        (
            moduleMenu(set(array
            (
                'modules'            => $deptTree,
                'activeKey'          => $type == 'bydept' ? $param : 0,
                'settingLink'        => createLink('dept', 'browse'),
                'closeLink'          => createLink('company', 'browse', "browseType={$browseType}&param=0&type={$type}"),
                'showDisplay'        => false,
                'filterMap'          => array(),
                'settingText'        => $lang->dept->manage,
                'appendSettingItems' => array(array('text' => $lang->user->importLDAP, 'url' => createLink('user', 'importLDAP')))
            )))
        ));
}
