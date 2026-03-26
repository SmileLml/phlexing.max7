<?php
#!/usr/bin/env php

include '../lib/design.ui.class.php';

zendata('approvalflow')->loadYaml('approvalflow')->gen(1);
zendata('approvalflowspec')->loadYaml('approvalflowspec')->gen(1);

$tester = new designTester();
$tester->login();

r($tester->getNodeType('发起人自选')) && p('message,status') && e('评审节点类型正确,SUCCESS'); //
r($tester->designFlow())              && p('message,status') && e('设计审批流成功,SUCCESS'); //
r($tester->getNodeName('审批节点1'))  && p('message,status') && e('评审节点名称正确,SUCCESS'); //

$tester->closeBrowser();
