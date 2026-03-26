<?php
$config->group->package->importBug->privs['bug-import']['edition']         .= ',open';
$config->group->package->importBug->privs['bug-exportTemplate']['edition'] .= ',open';

$config->group->package->importTask->privs['task-import']['edition']		 .= ',open';
$config->group->package->importTask->privs['task-exportTemplate']['edition'] .= ',open';

$config->group->package->importStory->privs['story-import']['edition']		   .= ',open';
$config->group->package->importStory->privs['story-exportTemplate']['edition'] .= ',open';

$config->group->package->importRequirement->privs['requirement-import']['edition'] 		   .= ',open';
$config->group->package->importRequirement->privs['requirement-exportTemplate']['edition'] .= ',open';

$config->group->package->importEpic->privs['epic-import']['edition']         .= ',open';
$config->group->package->importEpic->privs['epic-exportTemplate']['edition'] .= ',open';

$config->group->package->user->privs['user-export']['edition']         .= ',open';
$config->group->package->user->privs['user-exportTemplate']['edition'] .= ',open';
$config->group->package->user->privs['user-import']['edition']         .= ',open';
