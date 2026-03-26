<?php
$config->review->ipdReviewPoint = new stdclass();
$config->review->ipdReviewPoint->concept   = array('TR1', 'CDCP');
$config->review->ipdReviewPoint->plan      = array('TR2', 'TR3', 'PDCP');
$config->review->ipdReviewPoint->develop   = array('TR4', 'TR4A', 'TR5');
$config->review->ipdReviewPoint->qualify   = array('TR6', 'ADCP');
$config->review->ipdReviewPoint->launch    = array();
$config->review->ipdReviewPoint->lifecycle = array('LDCP');

$config->review->ipdStageOrder = array('concept', 'plan', 'develop', 'qualify', 'launch', 'lifecycle');
$config->review->ipdPointOrder = array('TR1', 'CDCP', 'TR2', 'TR3', 'PDCP', 'TR4', 'TR4A', 'TR5', 'TR6', 'ADCP', 'LDCP');
