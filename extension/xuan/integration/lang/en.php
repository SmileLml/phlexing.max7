<?php
$lang->integration->common     = 'Integration';
$lang->integration->switch     = 'Toggle';
$lang->integration->switchList = array('Disabled', 'Enabled');

$lang->integration->type = 'Type';

$lang->integration->office        = 'Office Integration';
$lang->integration->collabora     = 'Collabora Online';
$lang->integration->collaboraPath = 'Collabora URL';

$lang->integration->error = new stdclass();
$lang->integration->error->cannotConnectToCollabora = 'Cannot connect to Collabora Online service, please check if Collabora Online is configured, running and connectable.';
$lang->integration->error->officeNotEnabled         = 'Office integration is disabled.';
$lang->integration->error->userNotFoundForRequest   = 'Cannot find corresponding user.';
$lang->integration->error->fileNotFoundForRequest   = 'Cannot find corresponding file.';
$lang->integration->error->filePreviewNotSupported  = 'Unable to preview this file.';
$lang->integration->error->buildIdentifierFail      = 'Unable to build preview parameters.';

$lang->integration->placeholder = new stdclass();
$lang->integration->placeholder->collabora = 'Collabora Online URL, e.g., https://192.168.1.2:9980';
