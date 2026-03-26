<?php
$config->group->package->manageEffort->privs['my-effort']['edition']          .= ',open';
$config->group->package->manageEffort->privs['effort-calendar']['edition']    .= ',open';
$config->group->package->manageEffort->privs['effort-batchCreate']['edition'] .= ',open';
$config->group->package->manageEffort->privs['effort-edit']['edition']        .= ',open';
$config->group->package->manageEffort->privs['effort-batchEdit']['edition']   .= ',open';
$config->group->package->manageEffort->privs['effort-view']['edition']        .= ',open';
$config->group->package->manageEffort->privs['effort-delete']['edition']      .= ',open';
$config->group->package->manageEffort->privs['effort-export']['edition']      .= ',open';

$config->group->package->companyEffort->privs['company-calendar']['edition'] .= ',open';
$config->group->package->companyEffort->privs['company-effort']['edition']   .= ',open';

$config->group->package->companyCalendar->privs['company-todo']['edition'] .= ',open';

$config->group->package->companyDataPermission->privs['company-allTodo']['edition']   .= ',open';
$config->group->package->companyDataPermission->privs['company-alleffort']['edition'] .= ',open';

$config->group->package->browseExecution->privs['execution-effortCalendar']['edition'] .= ',open';

$config->group->package->taskEffort->privs['execution-taskEffort']['edition']        .= ',open';
$config->group->package->taskEffort->privs['execution-computeTaskEffort']['edition'] .= ',open';

$config->group->package->commonEffort->privs['effort-createForObject']['edition'] .= ',open';

$config->group->package->manageExecutionEffort->privs['execution-effort']['edition'] .= ',open';

$config->group->package->taskCalendar->privs['execution-calendar']['edition'] .= ',open';

$config->group->package->browseTodo->privs['todo-calendar']['edition'] .= ',open';

$config->group->package->companyTeam->privs['user-effortcalendar']['edition'] .= ',open';
$config->group->package->companyTeam->privs['user-todocalendar']['edition']   .= ',open';
$config->group->package->companyTeam->privs['user-effort']['edition']         .= ',open';