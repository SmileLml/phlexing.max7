<?php
$config->ldap = new stdclass();
$config->ldap->set = new stdclass();
$config->ldap->set->requiredFields = 'host,port,baseDN,account,admin';

$config->ldap->import = new stdClass();
$config->ldap->import->search['module'] = 'ldap';

$config->ldap->computerTags = 'computer';

$config->ldap->autoCreate = 1;
