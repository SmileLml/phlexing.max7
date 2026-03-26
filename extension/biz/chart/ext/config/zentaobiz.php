<?php
$config->chart->create = new stdclass();
$config->chart->create->requiredFields = 'name,type,group';

$config->chart->edit = new stdclass();
$config->chart->edit->requiredFields = 'name,type,group';

$config->chart->design = new stdclass();
$config->chart->design->requiredFields = 'name,group';

$config->chart->multiColumn = array('cluBarX' => 'yaxis', 'cluBarY' => 'yaxis', 'radar' => 'yaxis', 'line' => 'yaxis', 'stackedBar' => 'yaxis', 'stackedBarY' => 'yaxis', 'waterpolo' => 'condition');
$config->chart->dateGroup   = array('pie' => 'group', 'cluBarX' => 'xaxis', 'cluBarY' => 'xaxis', 'radar' => 'xaxis', 'line' => 'xaxis', 'stackedBar' => 'xaxis', 'stackedBarY' => 'xaxis');

$config->chart->checkForm = array();
$config->chart->checkForm['line']        = array('cantequal' => 'xaxis,yaxis');
$config->chart->checkForm['cluBarX']     = array('cantequal' => 'xaxis,yaxis');
$config->chart->checkForm['cluBarY']     = array('cantequal' => 'xaxis,yaxis');
$config->chart->checkForm['radar']       = array('cantequal' => 'xaxis,yaxis');
$config->chart->checkForm['stackedBar']  = array('cantequal' => 'xaxis,yaxis');
$config->chart->checkForm['stackedBarY'] = array('cantequal' => 'xaxis,yaxis');
global $lang;
$config->chart->settings = array();
$config->chart->settings['cluBarX'] = array();
$config->chart->settings['cluBarX']['xaxis']   = array();
$config->chart->settings['cluBarX']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 8);

$config->chart->settings['cluBarX']['yaxis']   = array();
$config->chart->settings['cluBarX']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 4);
$config->chart->settings['cluBarX']['yaxis'][] = array('field' => 'valOrAgg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->chart->aggType, 'col' => 4);

$config->chart->settings['cluBarY'] = array();
$config->chart->settings['cluBarY']['xaxis']   = array();
$config->chart->settings['cluBarY']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 8);

$config->chart->settings['cluBarY']['yaxis']   = array();
$config->chart->settings['cluBarY']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 4);
$config->chart->settings['cluBarY']['yaxis'][] = array('field' => 'valOrAgg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->chart->aggType, 'col' => 4);

$config->chart->settings['stackedBarY'] = array();
$config->chart->settings['stackedBarY']['xaxis']   = array();
$config->chart->settings['stackedBarY']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 8);

$config->chart->settings['stackedBarY']['yaxis']   = array();
$config->chart->settings['stackedBarY']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 4);
$config->chart->settings['stackedBarY']['yaxis'][] = array('field' => 'valOrAgg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->chart->aggType, 'col' => 4);

$config->chart->settings['line'] = array();
$config->chart->settings['line']['xaxis']   = array();
$config->chart->settings['line']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 8);

$config->chart->settings['line']['yaxis']   = array();
$config->chart->settings['line']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 4);
$config->chart->settings['line']['yaxis'][] = array('field' => 'valOrAgg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->chart->aggType, 'col' => 4);

$config->chart->settings['pie'] = array();
$config->chart->settings['pie']['group']   = array();
$config->chart->settings['pie']['group'][] = array('field' => 'group', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 8);

$config->chart->settings['pie']['metric']   = array();
$config->chart->settings['pie']['metric'][] = array('field' => 'metric',  'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 4);
$config->chart->settings['pie']['metric'][] = array('field' => 'valOrAgg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->chart->aggType, 'col' => 4);

$config->chart->settings['radar'] = array();
$config->chart->settings['radar']['xaxis']   = array();
$config->chart->settings['radar']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 8);

$config->chart->settings['radar']['yaxis']   = array();
$config->chart->settings['radar']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 4);
$config->chart->settings['radar']['yaxis'][] = array('field' => 'valOrAgg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->chart->aggType, 'col' => 4);

$config->chart->settings['stackedBar'] = array();
$config->chart->settings['stackedBar']['xaxis']   = array();
$config->chart->settings['stackedBar']['xaxis'][] = array('field' => 'xaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 8);

$config->chart->settings['stackedBar']['yaxis']   = array();
$config->chart->settings['stackedBar']['yaxis'][] = array('field' => 'yaxis', 'type' => 'select', 'options' => 'field', 'required' => true, 'placeholder' => $lang->chart->chooseField, 'col' => 4);
$config->chart->settings['stackedBar']['yaxis'][] = array('field' => 'valOrAgg', 'type' => 'select', 'options' => 'aggList', 'required' => false, 'placeholder' => $lang->chart->aggType, 'col' => 4);

$config->chart->settings['waterpolo']['goal'] = array();
$config->chart->settings['waterpolo']['goal'][] = array('field' => 'goal', 'type' => 'select', 'required' => true);
$config->chart->settings['waterpolo']['goal'][] = array('field' => 'calc', 'type' => 'select', 'required' => true);
$config->chart->settings['waterpolo']['condition'][] = array('field' => 'field', 'type' => 'select', 'required' => true);
$config->chart->settings['waterpolo']['condition'][] = array('field' => 'condition', 'type' => 'input', 'required' => true);
$config->chart->settings['waterpolo']['condition'][] = array('field' => 'value', 'type' => 'select', 'required' => true);

$config->chart->settings['testingReport'] = array('field' => array('type' => 'td'));

$config->chart->transTypes = array();
$config->chart->transTypes['int']      = 'number';
$config->chart->transTypes['float']    = 'number';
$config->chart->transTypes['double']   = 'number';
$config->chart->transTypes['datetime'] = 'date';
$config->chart->transTypes['date']     = 'date';

$config->chart->ipdFields['story']   = array('BSA', 'duration', 'demand', 'submitedBy', 'roadmap');
$config->chart->ipdFields['project'] = array('charter', 'category');
$config->chart->ipdFields['product'] = array('PMT');
