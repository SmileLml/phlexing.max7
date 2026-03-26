<?php
global $lang;
$config->custom->canAdd['feedback'] = 'closedReasonList,typeList,priList';
$config->custom->canAdd['ticket']   = 'priList,typeList,closedReasonList';

if(!empty($_SESSION['user']->feedback) or !empty($_COOKIE['feedbackView']))
{
    $config->custom->moblieHidden['main'][] = 'ops';

    $config->custom->noModuleMenu['todo']     = 'todo';
    $config->custom->noModuleMenu['my']       = 'my';
    $config->custom->noModuleMenu['feedback'] = 'feedback';
    $config->custom->noModuleMenu['faq']      = 'faq';
}
$config->custom->moblieHidden['my']         = array('changePassword', 'manageContacts', 'profile', 'review');
$config->custom->moblieHidden['oa']         = array('holiday', 'review');
$config->custom->moblieHidden['feedback'][] = 'products';
$config->custom->moblieHidden['ops'][]      = 'setting';

$config->custom->requiredModules[82] = 'feedback';
$config->custom->fieldList['feedback']['create'] = 'module,type,feedbackBy,notifyEmail';
$config->custom->fieldList['feedback']['edit']   = 'module,type,feedbackBy,notifyEmail';

$config->custom->requiredModules[83] = 'ticket';
$config->custom->fieldList['ticket']['create'] = 'module,type,openedBuild,notifyEmail';
$config->custom->fieldList['ticket']['edit']   = 'module,type,openedBuild,notifyEmail';
array_splice($config->custom->allFeatures, -2, 0, array('oa', 'ops', 'feedback', 'traincourse', 'workflow'));

$config->custom->customFields['feedback'] = array('custom' => array('batchCreateFields'));
$config->custom->customFields['ticket']   = array('custom' => array('batchCreateFields'));

$config->custom->browseRelation = new stdclass();
$config->custom->browseRelation->actionList = array();
$config->custom->browseRelation->actionList['edit'] = array();
$config->custom->browseRelation->actionList['edit']['icon']        = 'edit';
$config->custom->browseRelation->actionList['edit']['hint']        = $lang->edit;
$config->custom->browseRelation->actionList['edit']['url']         = array('module' => 'custom', 'method' => 'editRelation', 'params' => 'id={key}');
$config->custom->browseRelation->actionList['edit']['data-toggle'] = 'modal';
$config->custom->browseRelation->actionList['edit']['data-size']   = 'sm';

$config->custom->browseRelation->actionList['delete'] = array();
$config->custom->browseRelation->actionList['delete']['icon']      = 'trash';
$config->custom->browseRelation->actionList['delete']['hint']      = $lang->delete;
$config->custom->browseRelation->actionList['delete']['url']       = array('module' => 'custom', 'method' => 'deleteRelation', 'params' => 'id={key}');
$config->custom->browseRelation->actionList['delete']['className'] = 'ajax-submit delete-relation-btn';

$config->custom->browseRelation->dtable = new stdclass();
$config->custom->browseRelation->dtable->fieldList = array();
$config->custom->browseRelation->dtable->fieldList['relation'] = array();
$config->custom->browseRelation->dtable->fieldList['relation']['title']    = $lang->custom->relation;
$config->custom->browseRelation->dtable->fieldList['relation']['type']     = 'category';
$config->custom->browseRelation->dtable->fieldList['relation']['sortType'] = false;

$config->custom->browseRelation->dtable->fieldList['relativeRelation'] = array();
$config->custom->browseRelation->dtable->fieldList['relativeRelation']['title']    = $lang->custom->relativeRelation;
$config->custom->browseRelation->dtable->fieldList['relativeRelation']['type']     = 'category';
$config->custom->browseRelation->dtable->fieldList['relativeRelation']['sortType'] = false;

$config->custom->browseRelation->dtable->fieldList['actions'] = array();
$config->custom->browseRelation->dtable->fieldList['actions']['type'] = 'actions';
$config->custom->browseRelation->dtable->fieldList['actions']['menu'] = array('edit', 'delete');
$config->custom->browseRelation->dtable->fieldList['actions']['list'] = $config->custom->browseRelation->actionList;

$config->custom->relateObjectList = array();
if($config->edition == 'ipd') $config->custom->relateObjectList['demand'] = $lang->demand->common;
$config->custom->relateObjectList['epic']        = $lang->ERCommon;
$config->custom->relateObjectList['requirement'] = $lang->URCommon;
$config->custom->relateObjectList['story']       = $lang->SRCommon;
$config->custom->relateObjectList['task']        = $lang->task->common;
$config->custom->relateObjectList['bug']         = $lang->bug->common;
$config->custom->relateObjectList['testcase']    = $lang->testcase->common;
$config->custom->relateObjectList['doc']         = $lang->doc->common;
$config->custom->relateObjectList['design']      = $lang->design->common;
$config->custom->relateObjectList['repocommit']  = $lang->repo->commit;
$config->custom->relateObjectList['feedback']    = $lang->feedback->common;
$config->custom->relateObjectList['ticket']      = $lang->ticket->common;

$config->custom->objectOwner = array();
$config->custom->objectOwner['product']   = array('epic', 'requirement', 'story', 'bug', 'testcase', 'feedback', 'ticket', 'release');
$config->custom->objectOwner['project']   = array('design', 'build');
$config->custom->objectOwner['execution'] = array('task', 'build');

$config->custom->relateObjectFields['epic']        = array('id', 'relation', 'pri', 'title', 'product', 'module', 'openedBy', 'assignedTo', 'status');
$config->custom->relateObjectFields['requirement'] = array('id', 'relation', 'pri', 'title', 'product', 'module', 'openedBy', 'assignedTo', 'status');
$config->custom->relateObjectFields['story']       = array('id', 'relation', 'pri', 'title', 'product', 'module', 'openedBy', 'assignedTo', 'status');
$config->custom->relateObjectFields['task']        = array('id', 'relation', 'pri', 'name', 'project', 'execution', 'openedBy', 'assignedTo', 'status');
$config->custom->relateObjectFields['bug']         = array('id', 'relation', 'pri', 'severity', 'title', 'product', 'project', 'openedBy', 'assignedTo', 'status');
$config->custom->relateObjectFields['testcase']    = array('id', 'relation', 'pri', 'title', 'product', 'openedBy', 'status');
$config->custom->relateObjectFields['doc']         = array('id', 'relation', 'title', 'addedBy', 'addedDate', 'editedBy', 'editedDate');
$config->custom->relateObjectFields['design']      = array('id', 'relation', 'name', 'product', 'project', 'createdBy', 'assignedTo');
$config->custom->relateObjectFields['repocommit']  = array('id', 'relation', 'revision', 'repo', 'committer', 'time');
$config->custom->relateObjectFields['feedback']    = array('id', 'relation', 'pri', 'title', 'product', 'module', 'openedBy', 'assignedTo', 'status');
$config->custom->relateObjectFields['ticket']      = array('id', 'relation', 'pri', 'title', 'product', 'module', 'openedBy', 'assignedTo', 'status');

$config->custom->relationPairs = array();
$config->custom->relationPairs['transferredto'] = 'transferredfrom';
$config->custom->relationPairs['twin']          = 'twin';
$config->custom->relationPairs['subdivideinto'] = 'subdividefrom';
$config->custom->relationPairs['generated']     = 'derivedfrom';
$config->custom->relationPairs['completedin']   = 'completedfrom';
$config->custom->relationPairs['interrated']    = 'interrated';

$config->custom->charterFiles = json_encode($lang->custom->charterFiles);
