<?php
$config->excel->editor['issue']       = array('desc', 'resolutionComment');
$config->excel->editor['nc']          = array('desc');
$config->excel->editor['risk']        = array('prevention', 'remedy', 'resolution');
$config->excel->editor['opportunity'] = array('desc', 'prevention', 'resolution');

$config->excel->freeze->issue       = 'pri';
$config->excel->freeze->nc          = 'listID';
$config->excel->freeze->risk        = 'pri';
$config->excel->freeze->opportunity = 'pri';
