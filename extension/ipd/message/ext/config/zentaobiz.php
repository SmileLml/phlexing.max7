<?php
$config->message->objectTypes['feedback'] = array('opened', 'edited', 'assigned', 'tostory', 'tobug', 'toticket', 'toepic', 'touserstory', 'totask', 'totodo', 'asked', 'replied', 'closed', 'deleted', 'reviewed', 'processed');

$config->message->available['mail']['feedback']    = $config->message->objectTypes['feedback'];
$config->message->available['webhook']['feedback'] = $config->message->objectTypes['feedback'];
$config->message->available['message']['feedback'] = $config->message->objectTypes['feedback'];
$config->message->available['sms']['feedback']     = $config->message->objectTypes['feedback'];
$config->message->available['xuanxuan']['feedback'] = $config->message->objectTypes['feedback'];

$config->message->objectTypes['ticket'] = array('opened', 'edited', 'assigned', 'started', 'finished', 'closed', 'activated');
$config->message->available['mail']['ticket']    = $config->message->objectTypes['ticket'];
$config->message->available['webhook']['ticket'] = $config->message->objectTypes['ticket'];
$config->message->available['message']['ticket'] = $config->message->objectTypes['ticket'];
$config->message->available['sms']['ticket']     = $config->message->objectTypes['ticket'];

if($config->vision == 'rnd')
{
    $config->message->objectTypes['deploy'] = array('created', 'edited', 'canceled', 'finished', 'deploypublished');
    $config->message->available['mail']['deploy']     = $config->message->objectTypes['deploy'];
    $config->message->available['webhook']['deploy']  = $config->message->objectTypes['deploy'];
    $config->message->available['message']['deploy']  = $config->message->objectTypes['deploy'];
    $config->message->available['sms']['deploy']      = $config->message->objectTypes['deploy'];
    $config->message->available['xuanxuan']['deploy'] = $config->message->objectTypes['deploy'];
}
