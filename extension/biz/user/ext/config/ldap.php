<?php
$config->user->importldap = new stdclass();
$config->user->importldap->dtable = new stdclass();
$config->user->importldap->dtable->fieldList['id']['name']  = 'id';
$config->user->importldap->dtable->fieldList['id']['title'] = $lang->user->id;
$config->user->importldap->dtable->fieldList['id']['type']  = 'checkID';
$config->user->importldap->dtable->fieldList['id']['fixed'] = false;

$config->user->importldap->dtable->fieldList['account']['name']  = 'account';
$config->user->importldap->dtable->fieldList['account']['title'] = $lang->user->account;
$config->user->importldap->dtable->fieldList['account']['type']  = 'text';
$config->user->importldap->dtable->fieldList['account']['width'] = '100px';

$config->user->importldap->dtable->fieldList['realname']['name']  = 'realname';
$config->user->importldap->dtable->fieldList['realname']['title'] = $lang->user->realname;
$config->user->importldap->dtable->fieldList['realname']['type']  = 'text';
$config->user->importldap->dtable->fieldList['realname']['width'] = '100px';

$config->user->importldap->dtable->fieldList['visions']['name']     = 'visions';
$config->user->importldap->dtable->fieldList['visions']['title']    = $lang->user->visions;
$config->user->importldap->dtable->fieldList['visions']['type']     = 'control';
$config->user->importldap->dtable->fieldList['visions']['control']  = array('type' => 'picker', 'props' => array('multiple' => true, 'required' => true, 'value' => 'rnd'));
$config->user->importldap->dtable->fieldList['visions']['width']    = '200px';
$config->user->importldap->dtable->fieldList['visions']['required'] = true;

$config->user->importldap->dtable->fieldList['link']['name']    = 'link';
$config->user->importldap->dtable->fieldList['link']['title']   = $lang->user->link;
$config->user->importldap->dtable->fieldList['link']['type']    = 'control';
$config->user->importldap->dtable->fieldList['link']['control'] = array('type' => 'picker');
$config->user->importldap->dtable->fieldList['link']['width']   = '100px';

$config->user->importldap->dtable->fieldList['dept']['name']    = 'dept';
$config->user->importldap->dtable->fieldList['dept']['title']   = $lang->user->dept;
$config->user->importldap->dtable->fieldList['dept']['type']    = 'control';
$config->user->importldap->dtable->fieldList['dept']['control'] = array('type' => 'picker');
$config->user->importldap->dtable->fieldList['dept']['width']   = '100px';

$config->user->importldap->dtable->fieldList['role']['name']     = 'role';
$config->user->importldap->dtable->fieldList['role']['title']    = $lang->user->role;
$config->user->importldap->dtable->fieldList['role']['type']     = 'control';
$config->user->importldap->dtable->fieldList['role']['control']  = array('type' => 'picker', 'props' => array('required' => true));
$config->user->importldap->dtable->fieldList['role']['width']    = '100px';
$config->user->importldap->dtable->fieldList['role']['required'] = true;

$config->user->importldap->dtable->fieldList['group']['name']    = 'group';
$config->user->importldap->dtable->fieldList['group']['title']   = $lang->user->group;
$config->user->importldap->dtable->fieldList['group']['type']    = 'control';
$config->user->importldap->dtable->fieldList['group']['control'] = array('type' => 'picker');
$config->user->importldap->dtable->fieldList['group']['width']   = '100px';

$config->user->importldap->dtable->fieldList['gender']['name']    = 'gender';
$config->user->importldap->dtable->fieldList['gender']['title']   = $lang->user->gender;
$config->user->importldap->dtable->fieldList['gender']['type']    = 'control';
$config->user->importldap->dtable->fieldList['gender']['control'] = array('type' => 'picker');
$config->user->importldap->dtable->fieldList['gender']['width']   = '100px';

$config->user->importldap->dtable->fieldList['qq']['name']    = 'qq';
$config->user->importldap->dtable->fieldList['qq']['title']   = $lang->user->qq;
$config->user->importldap->dtable->fieldList['qq']['type']    = 'control';
$config->user->importldap->dtable->fieldList['qq']['control'] = 'input';
$config->user->importldap->dtable->fieldList['qq']['width']   = '100px';
