<?php
global $lang,$config, $app;
$app->loadLang('issue');
$app->loadLang('risk');
$app->loadLang('auditplan');
$app->loadLang('meeting');
$app->loadLang('nc');
$app->loadLang('review');
$app->loadLang('cm');
$app->loadLang('baseline');
$app->loadConfig('review');
$app->loadConfig('meeting');

$config->my->issue = new stdclass();
$config->my->issue->actionList = array();
$config->my->issue->actionList['confirm']['icon']        = 'ok';
$config->my->issue->actionList['confirm']['text']        = $lang->issue->confirm;
$config->my->issue->actionList['confirm']['hint']        = $lang->issue->confirm;
$config->my->issue->actionList['confirm']['url']         = array('module' => 'issue', 'method' => 'confirm', 'params' => 'issueID={id}');
$config->my->issue->actionList['confirm']['data-toggle'] = 'modal';
$config->my->issue->actionList['confirm']['data-size']   = 'lg';

$config->my->issue->actionList['resolve']['icon']        = 'checked';
$config->my->issue->actionList['resolve']['text']        = $lang->issue->resolve;
$config->my->issue->actionList['resolve']['hint']        = $lang->issue->resolve;
$config->my->issue->actionList['resolve']['url']         = array('module' => 'issue', 'method' => 'resolve', 'params' => 'issueID={id}');
$config->my->issue->actionList['resolve']['data-toggle'] = 'modal';
$config->my->issue->actionList['resolve']['data-size']   = 'lg';

$config->my->issue->actionList['close']['icon']        = 'off';
$config->my->issue->actionList['close']['text']        = $lang->issue->close;
$config->my->issue->actionList['close']['hint']        = $lang->issue->close;
$config->my->issue->actionList['close']['url']         = array('module' => 'issue', 'method' => 'close', 'params' => 'issueID={id}');
$config->my->issue->actionList['close']['data-toggle'] = 'modal';
$config->my->issue->actionList['close']['data-size']   = 'lg';

$config->my->issue->actionList['cancel']['icon']        = 'ban-circle';
$config->my->issue->actionList['cancel']['text']        = $lang->issue->cancel;
$config->my->issue->actionList['cancel']['hint']        = $lang->issue->cancel;
$config->my->issue->actionList['cancel']['url']         = array('module' => 'issue', 'method' => 'cancel', 'params' => 'issueID={id}');
$config->my->issue->actionList['cancel']['data-toggle'] = 'modal';
$config->my->issue->actionList['cancel']['data-size']   = 'lg';

$config->my->issue->actionList['activate']['icon']        = 'magic';
$config->my->issue->actionList['activate']['text']        = $lang->issue->activate;
$config->my->issue->actionList['activate']['hint']        = $lang->issue->activate;
$config->my->issue->actionList['activate']['url']         = array('module' => 'issue', 'method' => 'activate', 'params' => 'issueID={id}');
$config->my->issue->actionList['activate']['data-toggle'] = 'modal';
$config->my->issue->actionList['activate']['data-size']   = 'lg';

$config->my->issue->actionList['createForObject']['icon']        = 'time';
$config->my->issue->actionList['createForObject']['text']        = $lang->issue->effort;
$config->my->issue->actionList['createForObject']['hint']        = $lang->issue->effort;
$config->my->issue->actionList['createForObject']['url']         = array('module' => 'effort', 'method' => 'createForObject', 'params' => 'objectType=issue&objectID={id}');
$config->my->issue->actionList['createForObject']['data-toggle'] = 'modal';

$config->my->issue->actionList['edit']['icon']        = 'edit';
$config->my->issue->actionList['edit']['text']        = $lang->issue->edit;
$config->my->issue->actionList['edit']['hint']        = $lang->issue->edit;
$config->my->issue->actionList['edit']['url']         = array('module' => 'issue', 'method' => 'edit', 'params' => 'issueID={id}');
$config->my->issue->actionList['edit']['data-toggle'] = 'modal';
$config->my->issue->actionList['edit']['data-size']   = 'lg';

$config->my->issue->actionList['delete']['icon']         = 'trash';
$config->my->issue->actionList['delete']['text']         = $lang->issue->delete;
$config->my->issue->actionList['delete']['hint']         = $lang->issue->delete;
$config->my->issue->actionList['delete']['url']          = array('module' => 'issue', 'method' => 'delete', 'params' => 'issueID={id}&from=project&confirm=yes');
$config->my->issue->actionList['delete']['className']    = 'ajax-submit';
$config->my->issue->actionList['delete']['data-confirm'] = array('message' => $lang->issue->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->my->issue->dtable = new stdclass();
$config->my->issue->dtable->fieldList = array();
$config->my->issue->dtable->fieldList['id']['name']  = 'id';
$config->my->issue->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->issue->dtable->fieldList['id']['type']  = 'id';

$config->my->issue->dtable->fieldList['title']['name']  = 'title';
$config->my->issue->dtable->fieldList['title']['title'] = $lang->issue->title;
$config->my->issue->dtable->fieldList['title']['type']  = 'title';
$config->my->issue->dtable->fieldList['title']['link']  = array('module' => 'issue', 'method' => 'view', 'params' => 'id={id}');
$config->my->issue->dtable->fieldList['title']['fixed'] = 'left';

$config->my->issue->dtable->fieldList['project']['name']  = 'project';
$config->my->issue->dtable->fieldList['project']['title'] = $lang->issue->project;
$config->my->issue->dtable->fieldList['project']['type']  = 'category';

$config->my->issue->dtable->fieldList['pri']['name']  = 'pri';
$config->my->issue->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->my->issue->dtable->fieldList['pri']['type']  = 'pri';

$config->my->issue->dtable->fieldList['severity']['name']  = 'severity';
$config->my->issue->dtable->fieldList['severity']['title'] = $lang->issue->severity;
$config->my->issue->dtable->fieldList['severity']['type']  = 'severity';

$config->my->issue->dtable->fieldList['type']['name']  = 'type';
$config->my->issue->dtable->fieldList['type']['title'] = $lang->issue->type;
$config->my->issue->dtable->fieldList['type']['type']  = 'category';
$config->my->issue->dtable->fieldList['type']['map']   = $lang->issue->typeList;

$config->my->issue->dtable->fieldList['owner']['name']  = 'owner';
$config->my->issue->dtable->fieldList['owner']['title'] = $lang->issue->owner;
$config->my->issue->dtable->fieldList['owner']['type']  = 'user';

$config->my->issue->dtable->fieldList['createdDate']['name']  = 'createdDate';
$config->my->issue->dtable->fieldList['createdDate']['title'] = $lang->issue->createdDate;
$config->my->issue->dtable->fieldList['createdDate']['type']  = 'date';

$config->my->issue->dtable->fieldList['assignedBy']['name']  = 'assignedBy';
$config->my->issue->dtable->fieldList['assignedBy']['title'] = $lang->issue->assignedBy;
$config->my->issue->dtable->fieldList['assignedBy']['type']  = 'user';

$config->my->issue->dtable->fieldList['assignedTo']['name']        = 'assignedTo';
$config->my->issue->dtable->fieldList['assignedTo']['title']       = $lang->issue->assignedTo;
$config->my->issue->dtable->fieldList['assignedTo']['type']        = 'assign';
$config->my->issue->dtable->fieldList['assignedTo']['currentUser'] = $app->user->account;
$config->my->issue->dtable->fieldList['assignedTo']['assignLink']  = helper::createLink('issue', 'assignTo', "riskID={id}");
$config->my->issue->dtable->fieldList['assignedTo']['data-size']   = 'lg';

$config->my->issue->dtable->fieldList['status']['name']      = 'status';
$config->my->issue->dtable->fieldList['status']['title']     = $lang->issue->status;
$config->my->issue->dtable->fieldList['status']['type']      = 'status';
$config->my->issue->dtable->fieldList['status']['statusMap'] = $lang->issue->statusList;

$config->my->issue->dtable->fieldList['actions']['name']     = 'actions';
$config->my->issue->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->issue->dtable->fieldList['actions']['type']     = 'actions';
$config->my->issue->dtable->fieldList['actions']['sortType'] = false;
$config->my->issue->dtable->fieldList['actions']['list']     = $config->my->issue->actionList;
$config->my->issue->dtable->fieldList['actions']['menu']     = array('confirm', 'resolve', 'close', 'cancel', 'activate', 'createForObject', 'edit', 'delete');

$config->my->risk = new stdclass();
$config->my->risk->actionList = array();
$config->my->risk->actionList['track']['icon']        = 'checked';
$config->my->risk->actionList['track']['text']        = $lang->risk->track;
$config->my->risk->actionList['track']['hint']        = $lang->risk->track;
$config->my->risk->actionList['track']['url']         = array('module' => 'risk', 'method' => 'track', 'params' => 'riskID={id}');
$config->my->risk->actionList['track']['data-toggle'] = 'modal';
$config->my->risk->actionList['track']['data-size']   = 'lg';

$config->my->risk->actionList['close']['icon']        = 'off';
$config->my->risk->actionList['close']['text']        = $lang->risk->close;
$config->my->risk->actionList['close']['hint']        = $lang->risk->close;
$config->my->risk->actionList['close']['url']         = array('module' => 'risk', 'method' => 'close', 'params' => 'riskID={id}');
$config->my->risk->actionList['close']['data-toggle'] = 'modal';
$config->my->risk->actionList['close']['data-size']   = 'lg';

$config->my->risk->actionList['cancel']['icon']        = 'ban-circle';
$config->my->risk->actionList['cancel']['text']        = $lang->risk->cancel;
$config->my->risk->actionList['cancel']['hint']        = $lang->risk->cancel;
$config->my->risk->actionList['cancel']['url']         = array('module' => 'risk', 'method' => 'cancel', 'params' => 'riskID={id}');
$config->my->risk->actionList['cancel']['data-toggle'] = 'modal';
$config->my->risk->actionList['cancel']['data-size']   = 'lg';

$config->my->risk->actionList['hangup']['icon']        = 'pause';
$config->my->risk->actionList['hangup']['text']        = $lang->risk->hangup;
$config->my->risk->actionList['hangup']['hint']        = $lang->risk->hangup;
$config->my->risk->actionList['hangup']['url']         = array('module' => 'risk', 'method' => 'hangup', 'params' => 'riskID={id}');
$config->my->risk->actionList['hangup']['data-toggle'] = 'modal';
$config->my->risk->actionList['hangup']['data-size']   = 'lg';

$config->my->risk->actionList['activate']['icon']        = 'magic';
$config->my->risk->actionList['activate']['text']        = $lang->risk->activate;
$config->my->risk->actionList['activate']['hint']        = $lang->risk->activate;
$config->my->risk->actionList['activate']['url']         = array('module' => 'risk', 'method' => 'activate', 'params' => 'riskID={id}');
$config->my->risk->actionList['activate']['data-toggle'] = 'modal';
$config->my->risk->actionList['activate']['data-size']   = 'lg';

$config->my->risk->actionList['createForObject']['icon']        = 'time';
$config->my->risk->actionList['createForObject']['text']        = $lang->risk->effort;
$config->my->risk->actionList['createForObject']['hint']        = $lang->risk->effort;
$config->my->risk->actionList['createForObject']['url']         = array('module' => 'effort', 'method' => 'createForObject', 'params' => 'objectType=risk&objectID={id}');
$config->my->risk->actionList['createForObject']['data-toggle'] = 'modal';

$config->my->risk->actionList['edit']['icon']        = 'edit';
$config->my->risk->actionList['edit']['text']        = $lang->risk->edit;
$config->my->risk->actionList['edit']['hint']        = $lang->risk->edit;
$config->my->risk->actionList['edit']['url']         = array('module' => 'risk', 'method' => 'edit', 'params' => 'riskID={id}');
$config->my->risk->actionList['edit']['data-toggle'] = 'modal';
$config->my->risk->actionList['edit']['data-size']   = 'lg';

$config->my->risk->dtable = new stdclass();
$config->my->risk->dtable->fieldList = array();
$config->my->risk->dtable->fieldList['id']['name']  = 'id';
$config->my->risk->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->risk->dtable->fieldList['id']['type']  = 'id';

$config->my->risk->dtable->fieldList['name']['name']  = 'name';
$config->my->risk->dtable->fieldList['name']['title'] = $lang->risk->name;
$config->my->risk->dtable->fieldList['name']['type']  = 'title';
$config->my->risk->dtable->fieldList['name']['link']  = array('module' => 'risk', 'method' => 'view', 'params' => 'id={id}');
$config->my->risk->dtable->fieldList['name']['fixed'] = 'left';

$config->my->risk->dtable->fieldList['project']['name']  = 'project';
$config->my->risk->dtable->fieldList['project']['title'] = $lang->my->ncProgram;
$config->my->risk->dtable->fieldList['project']['type']  = 'category';

$config->my->risk->dtable->fieldList['pri']['name']    = 'pri';
$config->my->risk->dtable->fieldList['pri']['title']   = $lang->priAB;
$config->my->risk->dtable->fieldList['pri']['type']    = 'pri';
$config->my->risk->dtable->fieldList['pri']['priList'] = $lang->risk->priList;

$config->my->risk->dtable->fieldList['rate']['name']  = 'rate';
$config->my->risk->dtable->fieldList['rate']['title'] = $lang->risk->rate;
$config->my->risk->dtable->fieldList['rate']['type']  = 'number';

$config->my->risk->dtable->fieldList['status']['name']      = 'status';
$config->my->risk->dtable->fieldList['status']['title']     = $lang->risk->status;
$config->my->risk->dtable->fieldList['status']['type']      = 'status';
$config->my->risk->dtable->fieldList['status']['statusMap'] = $lang->risk->statusList;

$config->my->risk->dtable->fieldList['category']['name']  = 'category';
$config->my->risk->dtable->fieldList['category']['title'] = $lang->risk->category;
$config->my->risk->dtable->fieldList['category']['type']  = 'category';
$config->my->risk->dtable->fieldList['category']['map']   = $lang->risk->categoryList;

$config->my->risk->dtable->fieldList['identifiedDate']['name']  = 'identifiedDate';
$config->my->risk->dtable->fieldList['identifiedDate']['title'] = $lang->risk->identifiedDate;
$config->my->risk->dtable->fieldList['identifiedDate']['type']  = 'date';

$config->my->risk->dtable->fieldList['assignedTo']['name']        = 'assignedTo';
$config->my->risk->dtable->fieldList['assignedTo']['title']       = $lang->risk->assignedTo;
$config->my->risk->dtable->fieldList['assignedTo']['type']        = 'assign';
$config->my->risk->dtable->fieldList['assignedTo']['currentUser'] = $app->user->account;
$config->my->risk->dtable->fieldList['assignedTo']['assignLink']  = helper::createLink('risk', 'assignTo', "riskID={id}");
$config->my->risk->dtable->fieldList['assignedTo']['data-size']   = 'lg';

$config->my->risk->dtable->fieldList['strategy']['name']  = 'strategy';
$config->my->risk->dtable->fieldList['strategy']['title'] = $lang->risk->strategy;
$config->my->risk->dtable->fieldList['strategy']['type']  = 'category';
$config->my->risk->dtable->fieldList['strategy']['map']   = $lang->risk->strategyList;

$config->my->risk->dtable->fieldList['actions']['name']     = 'actions';
$config->my->risk->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->risk->dtable->fieldList['actions']['type']     = 'actions';
$config->my->risk->dtable->fieldList['actions']['sortType'] = false;
$config->my->risk->dtable->fieldList['actions']['list']     = $config->my->risk->actionList;
$config->my->risk->dtable->fieldList['actions']['menu']     = array('track', 'close', 'cancel', 'hangup', 'activate', 'createForObject', 'edit');

$config->my->auditplan = new stdclass();
$config->my->auditplan->actionList = array();
$config->my->auditplan->actionList['check']['icon']        = 'confirm';
$config->my->auditplan->actionList['check']['text']        = $lang->auditplan->check;
$config->my->auditplan->actionList['check']['hint']        = $lang->auditplan->check;
$config->my->auditplan->actionList['check']['url']         = array('module' => 'auditplan', 'method' => 'check', 'params' => 'auditplanID={id}&projectID={project}', 'onlybody' => true);
$config->my->auditplan->actionList['check']['data-toggle'] = 'modal';
$config->my->auditplan->actionList['check']['data-size']   = 'lg';
$config->my->auditplan->actionList['check']['data-type']   = 'iframe';

$config->my->auditplan->actionList['result']['icon']        = 'list-alt';
$config->my->auditplan->actionList['result']['text']        = $lang->auditplan->result;
$config->my->auditplan->actionList['result']['hint']        = $lang->auditplan->result;
$config->my->auditplan->actionList['result']['url']         = array('module' => 'auditplan', 'method' => 'result', 'params' => 'auditplanID={id}', 'onlybody' => true);
$config->my->auditplan->actionList['result']['data-toggle'] = 'modal';
$config->my->auditplan->actionList['result']['data-size']   = 'lg';
$config->my->auditplan->actionList['result']['data-type']   = 'iframe';

$config->my->auditplan->actionList['nc']['icon']        = 'bug';
$config->my->auditplan->actionList['nc']['text']        = $lang->auditplan->nc;
$config->my->auditplan->actionList['nc']['hint']        = $lang->auditplan->nc;
$config->my->auditplan->actionList['nc']['url']         = array('module' => 'auditplan', 'method' => 'nc', 'params' => 'auditplanID={id}', 'onlybody' => true);
$config->my->auditplan->actionList['nc']['data-toggle'] = 'modal';
$config->my->auditplan->actionList['nc']['data-size']   = 'lg';
$config->my->auditplan->actionList['nc']['data-type']   = 'iframe';

$config->my->auditplan->actionList['edit']['icon']        = 'edit';
$config->my->auditplan->actionList['edit']['text']        = $lang->auditplan->edit;
$config->my->auditplan->actionList['edit']['hint']        = $lang->auditplan->edit;
$config->my->auditplan->actionList['edit']['url']         = array('module' => 'auditplan', 'method' => 'edit', 'params' => 'auditplanID={id}&from=my', 'onlybody' => true);
$config->my->auditplan->actionList['edit']['data-toggle'] = 'modal';
$config->my->auditplan->actionList['edit']['data-size']   = 'lg';
$config->my->auditplan->actionList['edit']['data-type']   = 'iframe';

$config->my->auditplan->actionList['delete']['icon']         = 'trash';
$config->my->auditplan->actionList['delete']['text']         = $lang->auditplan->delete;
$config->my->auditplan->actionList['delete']['hint']         = $lang->auditplan->delete;
$config->my->auditplan->actionList['delete']['url']          = array('module' => 'auditplan', 'method' => 'delete', 'params' => 'auditplanID={id}&confirm=yes');
$config->my->auditplan->actionList['delete']['className']    = 'ajax-submit';
$config->my->auditplan->actionList['delete']['data-confirm'] = array('message' => $lang->auditplan->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->my->auditplan->dtable = new stdclass();
$config->my->auditplan->dtable->fieldList = array();
$config->my->auditplan->dtable->fieldList['id']['name']  = 'id';
$config->my->auditplan->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->auditplan->dtable->fieldList['id']['type']  = 'id';

$config->my->auditplan->dtable->fieldList['process']['name']  = 'process';
$config->my->auditplan->dtable->fieldList['process']['title'] = $lang->auditplan->process;
$config->my->auditplan->dtable->fieldList['process']['type']  = 'category';

$config->my->auditplan->dtable->fieldList['objectID']['name']  = 'objectID';
$config->my->auditplan->dtable->fieldList['objectID']['title'] = $lang->auditplan->objectID;
$config->my->auditplan->dtable->fieldList['objectID']['type']  = 'text';

$config->my->auditplan->dtable->fieldList['project']['name']  = 'project';
$config->my->auditplan->dtable->fieldList['project']['title'] = $lang->auditplan->project;
$config->my->auditplan->dtable->fieldList['project']['type']  = 'category';
$config->my->auditplan->dtable->fieldList['project']['link']  = array('module' => 'auditplan', 'method' => 'browse', 'params' => 'projectID={project}');

$config->my->auditplan->dtable->fieldList['execution']['name']  = 'execution';
$config->my->auditplan->dtable->fieldList['execution']['title'] = $lang->auditplan->execution;
$config->my->auditplan->dtable->fieldList['execution']['type']  = 'category';
$config->my->auditplan->dtable->fieldList['execution']['link']  = array('module' => 'execution', 'method' => 'task', 'params' => 'executionID={execution}');

$config->my->auditplan->dtable->fieldList['objectType']['name']  = 'objectType';
$config->my->auditplan->dtable->fieldList['objectType']['title'] = $lang->auditplan->objectType;
$config->my->auditplan->dtable->fieldList['objectType']['type']  = 'category';
$config->my->auditplan->dtable->fieldList['objectType']['map']   = $lang->auditplan;

$config->my->auditplan->dtable->fieldList['status']['name']      = 'status';
$config->my->auditplan->dtable->fieldList['status']['title']     = $lang->auditplan->status;
$config->my->auditplan->dtable->fieldList['status']['type']      = 'status';
$config->my->auditplan->dtable->fieldList['status']['statusMap'] = $lang->auditplan->statusList;

$config->my->auditplan->dtable->fieldList['checkDate']['name']  = 'checkDate';
$config->my->auditplan->dtable->fieldList['checkDate']['title'] = $lang->auditplan->checkDate;
$config->my->auditplan->dtable->fieldList['checkDate']['type']  = 'date';

$config->my->auditplan->dtable->fieldList['actions']['name']     = 'actions';
$config->my->auditplan->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->auditplan->dtable->fieldList['actions']['type']     = 'actions';
$config->my->auditplan->dtable->fieldList['actions']['sortType'] = false;
$config->my->auditplan->dtable->fieldList['actions']['list']     = $config->my->auditplan->actionList;
$config->my->auditplan->dtable->fieldList['actions']['menu']     = array('check', 'result', 'nc', 'edit', 'delete');

$config->my->nc = new stdclass();
$config->my->nc->dtable = new stdclass();
$config->my->nc->dtable->fieldList = array();
$config->my->nc->dtable->fieldList['id']['name']  = 'id';
$config->my->nc->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->nc->dtable->fieldList['id']['type']  = 'id';

$config->my->nc->dtable->fieldList['title']['name']  = 'title';
$config->my->nc->dtable->fieldList['title']['title'] = $lang->my->ncName;
$config->my->nc->dtable->fieldList['title']['type']  = 'title';
$config->my->nc->dtable->fieldList['title']['link']  = array('module' => 'nc', 'method' => 'view', 'params' => 'ncID={id}');
$config->my->nc->dtable->fieldList['title']['fixed'] = 'left';

$config->my->nc->dtable->fieldList['project']['name']  = 'project';
$config->my->nc->dtable->fieldList['project']['title'] = $lang->my->project;
$config->my->nc->dtable->fieldList['project']['type']  = 'category';

$config->my->nc->dtable->fieldList['severity']['name']         = 'severity';
$config->my->nc->dtable->fieldList['severity']['title']        = $lang->my->ncSeverity;
$config->my->nc->dtable->fieldList['severity']['type']         = 'severity';
$config->my->nc->dtable->fieldList['severity']['severityList'] = $lang->nc->severityList;

$config->my->nc->dtable->fieldList['status']['name']      = 'status';
$config->my->nc->dtable->fieldList['status']['title']     = $lang->my->ncStatus;
$config->my->nc->dtable->fieldList['status']['type']      = 'status';
$config->my->nc->dtable->fieldList['status']['statusMap'] = $lang->nc->statusList;

$config->my->nc->dtable->fieldList['createdBy']['name']  = 'createdBy';
$config->my->nc->dtable->fieldList['createdBy']['title'] = $lang->my->ncCreatedBy;
$config->my->nc->dtable->fieldList['createdBy']['type']  = 'user';

$config->my->nc->dtable->fieldList['createdDate']['name']  = 'createdDate';
$config->my->nc->dtable->fieldList['createdDate']['title'] = $lang->my->ncCreatedDate;
$config->my->nc->dtable->fieldList['createdDate']['type']  = 'date';

$config->my->meeting = new stdclass();
$config->my->meeting->dtable = new stdclass();
$config->my->meeting->dtable->fieldList = array();
$config->my->meeting->dtable->fieldList['id']['name']  = 'id';
$config->my->meeting->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->meeting->dtable->fieldList['id']['type']  = 'id';

$config->my->meeting->dtable->fieldList['name']['name']     = 'name';
$config->my->meeting->dtable->fieldList['name']['title']    = $lang->meeting->name;
$config->my->meeting->dtable->fieldList['name']['type']     = 'title';
$config->my->meeting->dtable->fieldList['name']['link']     = array('module' => 'meeting', 'method' => 'view', 'params' => 'meetingID={id}');
$config->my->meeting->dtable->fieldList['name']['fixed']    = 'left';
$config->my->meeting->dtable->fieldList['name']['data-app'] = 'my';

$config->my->meeting->dtable->fieldList['mode']['name']  = 'mode';
$config->my->meeting->dtable->fieldList['mode']['title'] = $lang->meeting->mode;
$config->my->meeting->dtable->fieldList['mode']['type']  = 'category';
$config->my->meeting->dtable->fieldList['mode']['map']   = $lang->meeting->modeList;
$config->my->meeting->dtable->fieldList['mode']['show']  = true;

$config->my->meeting->dtable->fieldList['dept']['name']  = 'dept';
$config->my->meeting->dtable->fieldList['dept']['title'] = $lang->meeting->dept;
$config->my->meeting->dtable->fieldList['dept']['type']  = 'category';
$config->my->meeting->dtable->fieldList['dept']['show']  = true;

$config->my->meeting->dtable->fieldList['project']['name']  = 'project';
$config->my->meeting->dtable->fieldList['project']['title'] = $lang->meeting->project;
$config->my->meeting->dtable->fieldList['project']['type']  = 'category';
$config->my->meeting->dtable->fieldList['project']['show']  = true;

$config->my->meeting->dtable->fieldList['execution']['name']  = 'execution';
$config->my->meeting->dtable->fieldList['execution']['title'] = $lang->meeting->execution;
$config->my->meeting->dtable->fieldList['execution']['type']  = 'category';
$config->my->meeting->dtable->fieldList['execution']['show']  = true;

$config->my->meeting->dtable->fieldList['date']['name']  = 'date';
$config->my->meeting->dtable->fieldList['date']['title'] = $lang->meeting->date;
$config->my->meeting->dtable->fieldList['date']['type']  = 'datetime';
$config->my->meeting->dtable->fieldList['date']['show']  = true;

$config->my->meeting->dtable->fieldList['room']['name']  = 'room';
$config->my->meeting->dtable->fieldList['room']['title'] = $lang->meeting->room;
$config->my->meeting->dtable->fieldList['room']['type']  = 'category';
$config->my->meeting->dtable->fieldList['room']['show']  = true;

$config->my->meeting->dtable->fieldList['host']['name']  = 'host';
$config->my->meeting->dtable->fieldList['host']['title'] = $lang->meeting->host;
$config->my->meeting->dtable->fieldList['host']['type']  = 'user';
$config->my->meeting->dtable->fieldList['host']['show']  = true;

$config->my->meeting->dtable->fieldList['minutedBy']['name']  = 'minutedBy';
$config->my->meeting->dtable->fieldList['minutedBy']['title'] = $lang->meeting->minutedBy;
$config->my->meeting->dtable->fieldList['minutedBy']['type']  = 'user';
$config->my->meeting->dtable->fieldList['minutedBy']['show']  = true;

$config->my->meeting->dtable->fieldList['actions']['name']     = 'actions';
$config->my->meeting->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->meeting->dtable->fieldList['actions']['type']     = 'actions';
$config->my->meeting->dtable->fieldList['actions']['sortType'] = false;
$config->my->meeting->dtable->fieldList['actions']['width']    = '80px';
$config->my->meeting->dtable->fieldList['actions']['list']     = $config->meeting->actionList;
$config->my->meeting->dtable->fieldList['actions']['menu']     = array('edit', 'minutes');

$config->my->myaudit = new stdclass();
$config->my->myaudit->dtable = new stdclass();
$config->my->myaudit->dtable->defaultField = array('id', 'title', 'product', 'category', 'version', 'status', 'reviewedBy', 'reviewer', 'createdBy', 'createdDate', 'deadline', 'lastReviewedDate', 'result', 'lastAuditedDate', 'auditResult', 'actions');
$config->my->myaudit->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->myaudit->dtable->fieldList['id']['type']     = 'id';
$config->my->myaudit->dtable->fieldList['id']['fixed']    = 'left';
$config->my->myaudit->dtable->fieldList['id']['sortType'] = true;
$config->my->myaudit->dtable->fieldList['id']['required'] = true;

$config->my->myaudit->dtable->fieldList['title']['title']    = $lang->review->title;
$config->my->myaudit->dtable->fieldList['title']['type']     = 'title';
$config->my->myaudit->dtable->fieldList['title']['fixed']    = 'left';
$config->my->myaudit->dtable->fieldList['title']['link']     = array('module' => 'review', 'method' => 'view', 'params' => "reviewID={id}");
$config->my->myaudit->dtable->fieldList['title']['required'] = true;
$config->my->myaudit->dtable->fieldList['title']['sortType'] = true;

$config->my->myaudit->dtable->fieldList['category']['title']   = $lang->review->object;
$config->my->myaudit->dtable->fieldList['category']['type']    = 'text';
$config->my->myaudit->dtable->fieldList['category']['show']    = true;
$config->my->myaudit->dtable->fieldList['category']['sortType'] = true;

$config->my->myaudit->dtable->fieldList['project']['title']   = $lang->my->projects;
$config->my->myaudit->dtable->fieldList['project']['type']    = 'text';
$config->my->myaudit->dtable->fieldList['project']['show']    = true;
$config->my->myaudit->dtable->fieldList['project']['sortType'] = true;

$config->my->myaudit->dtable->fieldList['product']['title']   = $lang->review->product;
$config->my->myaudit->dtable->fieldList['product']['type']    = 'text';
$config->my->myaudit->dtable->fieldList['product']['show']    = true;
$config->my->myaudit->dtable->fieldList['product']['sortType'] = true;

$config->my->myaudit->dtable->fieldList['version']['title']    = $lang->review->version;
$config->my->myaudit->dtable->fieldList['version']['width']    = '180';
$config->my->myaudit->dtable->fieldList['version']['show']     = true;
$config->my->myaudit->dtable->fieldList['version']['sortType'] = true;

$config->my->myaudit->dtable->fieldList['status']['title']    = $lang->review->status;
$config->my->myaudit->dtable->fieldList['status']['width']    = '100';
$config->my->myaudit->dtable->fieldList['status']['show']     = true;
$config->my->myaudit->dtable->fieldList['status']['map']      = $lang->review->statusList;
$config->my->myaudit->dtable->fieldList['status']['sortType'] = true;

$config->my->myaudit->dtable->fieldList['reviewedBy']['title']    = $lang->review->reviewedBy;
$config->my->myaudit->dtable->fieldList['reviewedBy']['width']    = '150';
$config->my->myaudit->dtable->fieldList['reviewedBy']['show']     = true;
$config->my->myaudit->dtable->fieldList['reviewedBy']['type']     = 'text';
$config->my->myaudit->dtable->fieldList['reviewedBy']['sortType'] = true;

$config->my->myaudit->dtable->fieldList['createdBy']['title']    = $lang->review->createdBy;
$config->my->myaudit->dtable->fieldList['createdBy']['width']    = '120';
$config->my->myaudit->dtable->fieldList['createdBy']['show']     = true;
$config->my->myaudit->dtable->fieldList['createdBy']['type']     = 'user';
$config->my->myaudit->dtable->fieldList['createdBy']['required'] = false;

$config->my->myaudit->dtable->fieldList['createdDate']['title']    = $lang->review->createdDate;
$config->my->myaudit->dtable->fieldList['createdDate']['width']    = '120';
$config->my->myaudit->dtable->fieldList['createdDate']['show']     = true;
$config->my->myaudit->dtable->fieldList['createdDate']['type']     = 'date';
$config->my->myaudit->dtable->fieldList['createdDate']['required'] = false;

$config->my->myaudit->dtable->fieldList['deadline']['title']    = $lang->review->deadline;
$config->my->myaudit->dtable->fieldList['deadline']['width']    = '120';
$config->my->myaudit->dtable->fieldList['deadline']['show']     = true;
$config->my->myaudit->dtable->fieldList['deadline']['type']     = 'date';
$config->my->myaudit->dtable->fieldList['deadline']['required'] = false;

$config->my->myaudit->dtable->fieldList['lastReviewedDate']['title']    = $lang->review->lastReviewedDate;
$config->my->myaudit->dtable->fieldList['lastReviewedDate']['width']    = '120';
$config->my->myaudit->dtable->fieldList['lastReviewedDate']['show']     = true;
$config->my->myaudit->dtable->fieldList['lastReviewedDate']['type']     = 'date';
$config->my->myaudit->dtable->fieldList['lastReviewedDate']['required'] = false;

$config->my->myaudit->dtable->fieldList['result']['title']     = $lang->review->result;
$config->my->myaudit->dtable->fieldList['result']['type']      = 'status';
$config->my->myaudit->dtable->fieldList['result']['statusMap'] = $lang->review->resultList;
$config->my->myaudit->dtable->fieldList['result']['width']     = '120';
$config->my->myaudit->dtable->fieldList['result']['show']      = true;
$config->my->myaudit->dtable->fieldList['result']['required']  = false;

$config->my->myaudit->dtable->fieldList['lastAuditedDate']['title']    = $lang->review->lastAuditedDate;
$config->my->myaudit->dtable->fieldList['lastAuditedDate']['width']    = '120';
$config->my->myaudit->dtable->fieldList['lastAuditedDate']['show']     = true;
$config->my->myaudit->dtable->fieldList['lastAuditedDate']['type']     = 'date';
$config->my->myaudit->dtable->fieldList['lastAuditedDate']['required'] = false;

$config->my->myaudit->dtable->fieldList['auditResult']['title']    = $lang->review->auditResult;
$config->my->myaudit->dtable->fieldList['auditResult']['width']    = '120';
$config->my->myaudit->dtable->fieldList['auditResult']['map']      = $lang->review->auditResultList;
$config->my->myaudit->dtable->fieldList['auditResult']['show']     = true;
$config->my->myaudit->dtable->fieldList['auditResult']['required'] = false;

$config->my->myaudit->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->myaudit->dtable->fieldList['actions']['type']     = 'actions';
$config->my->myaudit->dtable->fieldList['actions']['width']    = '140';
$config->my->myaudit->dtable->fieldList['actions']['sortType'] = false;
$config->my->myaudit->dtable->fieldList['actions']['fixed']    = 'right';
$config->my->myaudit->dtable->fieldList['actions']['list']     = $config->review->actionList;
$config->my->myaudit->dtable->fieldList['actions']['menu']     = array('submit', 'recall', 'assess', 'progress', 'report', 'toAudit', 'audit', 'createBaseline', 'edit', 'delete');

$config->my->baseline = new stdclass();
$config->my->baseline->actionList['edit']['icon'] = 'edit';
$config->my->baseline->actionList['edit']['text'] = $lang->edit;
$config->my->baseline->actionList['edit']['hint'] = $lang->edit;
$config->my->baseline->actionList['edit']['url']  = array('module' => 'cm', 'method' => 'edit', 'params' => 'baselineID={id}');

$config->my->baseline->actionList['delete']['icon']         = 'trash';
$config->my->baseline->actionList['delete']['text']         = $lang->delete;
$config->my->baseline->actionList['delete']['hint']         = $lang->delete;
$config->my->baseline->actionList['delete']['url']          = array('module' => 'cm', 'method' => 'delete', 'params' => 'baselineID={id}&confirm=yes');
$config->my->baseline->actionList['delete']['className']    = 'ajax-submit';
$config->my->baseline->actionList['delete']['data-confirm'] = array('message' => $lang->cm->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->my->baseline->dtable = new stdclass();
$config->my->baseline->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->baseline->dtable->fieldList['id']['name']     = 'id';
$config->my->baseline->dtable->fieldList['id']['type']     = 'id';
$config->my->baseline->dtable->fieldList['id']['fixed']    = 'left';
$config->my->baseline->dtable->fieldList['id']['sortType'] = true;

$config->my->baseline->dtable->fieldList['category']['title']    = $lang->cm->object;
$config->my->baseline->dtable->fieldList['category']['name']     = 'category';
$config->my->baseline->dtable->fieldList['category']['type']     = 'category';
$config->my->baseline->dtable->fieldList['category']['map']      = $lang->baseline->objectList;
$config->my->baseline->dtable->fieldList['category']['align']    = 'left';
$config->my->baseline->dtable->fieldList['category']['sortType'] = true;
$config->my->baseline->dtable->fieldList['category']['fixed']    = 'left';
$config->my->baseline->dtable->fieldList['category']['width']    = '150';

$config->my->baseline->dtable->fieldList['title']['title'] = $lang->cm->title;
$config->my->baseline->dtable->fieldList['title']['name']  = 'title';
$config->my->baseline->dtable->fieldList['title']['type']  = 'title';
$config->my->baseline->dtable->fieldList['title']['link']  = array('module' => 'cm', 'method' => 'view', 'params' => 'id={id}');

$config->my->baseline->dtable->fieldList['version']['title']    = $lang->cm->version;
$config->my->baseline->dtable->fieldList['version']['name']     = 'version';
$config->my->baseline->dtable->fieldList['version']['width']    = '160';
$config->my->baseline->dtable->fieldList['version']['type']     = 'text';
$config->my->baseline->dtable->fieldList['version']['sortType'] = true;

$config->my->baseline->dtable->fieldList['project']['title']    = $lang->my->projects;
$config->my->baseline->dtable->fieldList['project']['name']     = 'project';
$config->my->baseline->dtable->fieldList['project']['type']     = 'text';
$config->my->baseline->dtable->fieldList['project']['sortType'] = true;

$config->my->baseline->dtable->fieldList['createdBy']['title'] = $lang->cm->createdBy;
$config->my->baseline->dtable->fieldList['createdBy']['name']  = 'createdBy';
$config->my->baseline->dtable->fieldList['createdBy']['type']  = 'user';

$config->my->baseline->dtable->fieldList['createdDate']['title'] = $lang->cm->createdDate;
$config->my->baseline->dtable->fieldList['createdDate']['name']  = 'createdDate';
$config->my->baseline->dtable->fieldList['createdDate']['type']  = 'date';

$config->my->baseline->dtable->fieldList['actions']['title'] = $lang->actions;
$config->my->baseline->dtable->fieldList['actions']['name']  = 'actions';
$config->my->baseline->dtable->fieldList['actions']['type']  = 'actions';
$config->my->baseline->dtable->fieldList['actions']['list']  = $config->my->baseline->actionList;
$config->my->baseline->dtable->fieldList['actions']['menu']  = array('edit', 'delete');
