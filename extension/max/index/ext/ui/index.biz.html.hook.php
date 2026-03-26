<?php
namespace zin;

global $config, $lang;
$edition     = $config->edition;
$editionName = $edition != 'open' ? $lang->{$edition . 'Name'} : $lang->pmsName;
$version     = $edition != 'open' ? $config->{$edition . 'Version'} : $config->version;
$versionName = $editionName . $version;

query('#appsToolbar')->find('.btn-zentao .text')->text($versionName);
