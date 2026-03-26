<?php
$lang->convert->jira->zentaoObjectList['feedback']    = 'Feedback';
$lang->convert->jira->zentaoObjectList['ticket']      = 'Ticket';
$lang->convert->jira->zentaoObjectList['add_custom']  = 'Add as workflow';

$lang->convert->jira->zentaoLinkTypeList['add_relation'] = 'Add Relation';

$lang->convert->confluence = new stdclass();
$lang->convert->confluence->import       = 'Import Confluence';
$lang->convert->confluence->notice       = 'Import Confluence Notice';
$lang->convert->confluence->domain       = 'Confluence Domain';
$lang->convert->confluence->admin        = 'Confluence Account';
$lang->convert->confluence->token        = 'Confluence Passworkd/Token';
$lang->convert->confluence->apiError     = 'Unable to connect to Confluence API interface, please check your Confluence domain name and account, password/Token information.';
$lang->convert->confluence->importDesc   = 'Use REST API interface to import data, please ensure smooth network connection with Confluence server.';
$lang->convert->confluence->importSpace  = 'The product space or project space must select a product or project.';
$lang->convert->confluence->mapSpaceDesc = 'The space that is only visible to me is mapped to the Zen path "My Space" by default, and a library with the same space name is automatically created. Other spaces are not mapped by default.';
$lang->convert->confluence->mapToZentao  = 'Map Confluence to Zentao';
$lang->convert->confluence->importUser   = 'Import Confluence User';
$lang->convert->confluence->importData   = 'Import Confluence Data';
$lang->convert->confluence->successfully = 'Done!';
$lang->convert->confluence->defaultSpace = 'Confluence Space';
$lang->convert->confluence->space        = 'Confluence Space';
$lang->convert->confluence->zentaoSpace  = 'Zentao Space';
$lang->convert->confluence->spaceKey     = 'Space Key';
$lang->convert->confluence->archived     = 'Archived';
$lang->convert->confluence->undefined    = 'Undefined';
$lang->convert->confluence->spaceMap     = 'Map Confluence space to Zentao';
$lang->convert->confluence->userNotice   = 'The system will automatically merge duplicate users based on their email addresses.';

$lang->convert->confluence->importSteps[1] = 'Backup ZenTao database, backup Confluence database.';
$lang->convert->confluence->importSteps[2] = 'Using ZenTao when importing data will cause performance pressure on the server, please try to ensure that no one is using ZenTao when importing data.';
$lang->convert->confluence->importSteps[3] = "Put the Confluence <strong class='text-red'> attachments</strong> directory under <strong class='text-red'>%s</strong>, Make sure you have enough disk space on the ZenTao server.";
$lang->convert->confluence->importSteps[4] = "To ensure the integrity of the imported data, please enter the domain name, administrator account, password/Token of the current Confluence environment.";
$lang->convert->confluence->importSteps[5] = "After the above steps are completed, click Next.";

$lang->convert->confluence->objectList['user']       = 'User';
$lang->convert->confluence->objectList['space']      = 'Space';
$lang->convert->confluence->objectList['folder']     = 'Folder';
$lang->convert->confluence->objectList['page']       = 'Page';
$lang->convert->confluence->objectList['embed']      = 'Embed';
$lang->convert->confluence->objectList['whiteboard'] = 'White Board';
$lang->convert->confluence->objectList['database']   = 'Database';
$lang->convert->confluence->objectList['blogpost']   = 'Blog';
$lang->convert->confluence->objectList['draft']      = 'Draft Page';
$lang->convert->confluence->objectList['archived']   = 'Archived Page';
$lang->convert->confluence->objectList['version']    = 'Version';
$lang->convert->confluence->objectList['comment']    = 'Comment';
$lang->convert->confluence->objectList['attachment'] = 'Attachment';

$lang->convert->confluence->zentaoSpaceList = array();
$lang->convert->confluence->zentaoSpaceList['product'] = 'Product Space';
$lang->convert->confluence->zentaoSpaceList['project'] = 'Project Space';
$lang->convert->confluence->zentaoSpaceList['custom']  = 'Team Space';
$lang->convert->confluence->zentaoSpaceList['mine']    = 'Private Space';

