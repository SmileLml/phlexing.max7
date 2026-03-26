<?php
$config->logonMethods[] = 'vm.register';
$config->logonMethods[] = 'host.register';

$config->routes['/vm/register']   = 'vm';
$config->routes['/host/register'] = 'host';
