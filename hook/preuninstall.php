<?php
$effortModel = $this->loadModel('effort');
if($effortModel) $effortModel->convertEffortToEst();
