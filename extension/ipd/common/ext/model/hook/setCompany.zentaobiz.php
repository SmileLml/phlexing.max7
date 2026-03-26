<?php
if(!empty($_SESSION['user']->feedback) or !empty($_COOKIE['feedbackView'])) $this->config->preferenceSetted = true;

if(!extension_loaded('ionCube Loader')) return parent::setCompany();
$this->loadExtension('zentaobiz')->setCompany();
