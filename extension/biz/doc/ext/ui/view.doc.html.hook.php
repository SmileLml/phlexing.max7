<?php
namespace zin;
global $lang, $config;

$type = data('type');

if($config->edition != 'open' && hasPriv('doc', 'diff'))
{
    $versionMenuOptions = array();
    $versionMenuOptions['header']       = jsRaw('window.getVersionHeader');
    $versionMenuOptions['footer']       = jsRaw('window.getVersionFooter');
    $versionMenuOptions['getItem']      = jsRaw('window.getDropdownItem');
    $versionMenuOptions['onClickItem']  = jsRaw('window.onClickDropdownItem');
    $versionMenuOptions['afterRender']  = jsRaw('window.afterRenderMenu');
    $versionMenuOptions['width']        = 200;
    $versionMenuOptions['checkOnClick'] = '.has-checkbox .item';

    $triggerProps = array();
    $triggerProps['diffLang']    = $lang->doc->diff;
    $triggerProps['cancelDiff']  = $lang->doc->cancelDiff;
    $triggerProps['confirmLang'] = $lang->confirm;
    $triggerProps['allVersion']  = $lang->doc->allVersion;
    $triggerProps['objectType']  = isset($type) ? $type : '';
    $triggerProps['docID']       = data('docID');

    query('#versionDropdown')
        ->prop('menu',         $versionMenuOptions)
        ->prop('triggerProps', $triggerProps);
}
