<?php
$config->workflowdatasource->require = new stdclass();
$config->workflowdatasource->require->create = 'name, datasource';
$config->workflowdatasource->require->edit   = 'name, datasource';

$config->workflowdatasource->notShowInLightMode = array('charterLevel', 'charterCategory', 'charterMarket', 'charterStatus', 'charterCloseReason', 'charterReviewResult', 'charterReviewStatus');

$config->workflowdatasource->modules['crm']   = array('order', 'contract', 'customer', 'contact', 'product', 'invoice', 'feedback', 'address');
$config->workflowdatasource->modules['oa']    = array('holiday', 'leave', 'overtime', 'trip');
$config->workflowdatasource->modules['proj']  = array('project', 'task');
$config->workflowdatasource->modules['doc']   = array('doc');
$config->workflowdatasource->modules['cash']  = array('balance', 'depositor', 'trade');
$config->workflowdatasource->modules['hr']    = array('commission');
$config->workflowdatasource->modules['psi']   = array('batch', 'order');
$config->workflowdatasource->modules['flow']  = array('workflow');
$config->workflowdatasource->modules['ameba'] = array('ameba', 'amebareport', 'budget', 'deal', 'fee', 'rule');
$config->workflowdatasource->modules['sys']   = array('common', 'company', 'entry', 'group', 'schema', 'store', 'tree', 'user', 'usercontact');

$config->workflowdatasource->methods['crm']['order']    = array('getPairs');
$config->workflowdatasource->methods['crm']['contract'] = array('getPairs');
$config->workflowdatasource->methods['crm']['customer'] = array('getPairs', 'combineSizeList', 'combineLevelList');
$config->workflowdatasource->methods['crm']['contact']  = array('getPairs', 'getCustomerPairs');
$config->workflowdatasource->methods['crm']['product']  = array('getPairs', 'getPropertyList');
$config->workflowdatasource->methods['crm']['invoice']  = array('getTrades', 'getMonthMoney');
$config->workflowdatasource->methods['crm']['feedback'] = array('getCustomerPairs');
$config->workflowdatasource->methods['crm']['address']  = array('getPairsByObject');

$config->workflowdatasource->methods['oa']['holiday']  = array('getYearPairs');
$config->workflowdatasource->methods['oa']['leave']    = array('getPairs');
$config->workflowdatasource->methods['oa']['overtime'] = array('getPairs');
$config->workflowdatasource->methods['oa']['trip']     = array('getPairs');

$config->workflowdatasource->methods['proj']['project'] = array('getMemberPairs', 'getPairs', 'getProjectsToImport');
$config->workflowdatasource->methods['proj']['task']    = array('getMemberPairs', 'getUserTaskPairs');

$config->workflowdatasource->methods['doc']['doc'] = array('getLibPairs', 'getAllLibsByType', 'getLimitLibs', 'getProjectModulePairs');

$config->workflowdatasource->methods['cash']['balance']   = array('getDateOptions', 'getDatePairs');
$config->workflowdatasource->methods['cash']['depositor'] = array('getPairs');
$config->workflowdatasource->methods['cash']['trade']     = array('getDatePairs', 'getSystemCategoryPairs', 'getIncomeCategories', 'getExpenseCategories', 'getSearchTraders', 'getSearchCategories');

$config->workflowdatasource->methods['hr']['commission'] = array('getCommissionedUsers');

$config->workflowdatasource->methods['psi']['batch']   = array('getPairs', 'getUninvoicedBatchList', 'getSentProductsByOrderID');
$config->workflowdatasource->methods['psi']['order']   = array('getPairs');
$config->workflowdatasource->methods['crm']['product'] = array('getPairs', 'getPropertyList');

$config->workflowdatasource->methods['flow']['workflow']           = array('getApps', 'getAppMenus', 'getBuildinModules', 'getPairs', 'getVersionPairs');
$config->workflowdatasource->methods['flow']['workflowaction']     = array('getPairs', 'getUsers2Notice');
$config->workflowdatasource->methods['flow']['workflowdatasource'] = array('getAppModules', 'getModuleMethods', 'getDefaultParams', 'getPairs');
$config->workflowdatasource->methods['flow']['workflowfield']      = array('getPairs', 'getFieldPairs', 'getCustomFields', 'getExportFields', 'getValueFields');
$config->workflowdatasource->methods['flow']['workflowlayout']     = array('getFields');
$config->workflowdatasource->methods['flow']['workflowhook']       = array('getTableFields');
$config->workflowdatasource->methods['flow']['workflowrule']       = array('getPairs');

$config->workflowdatasource->methods['ameba']['ameba']       = array('getLaborCategories', 'getIncomeCategories');
$config->workflowdatasource->methods['ameba']['amebareport'] = array('getWorkingDates');
$config->workflowdatasource->methods['ameba']['budget']      = array('getYearList', 'getWeekList', 'getCategoryList');
$config->workflowdatasource->methods['ameba']['deal']        = array('getTradePairs', 'getCategoryPairs', 'getDeptPairs');
$config->workflowdatasource->methods['ameba']['fee']         = array('getYearList', 'getCategoryPairs', 'getDeptPairs', 'getDeptUserCount');
$config->workflowdatasource->methods['ameba']['rule']        = array('getPairs', 'getYearList', 'getCategoryList', 'getDeptList', 'getProductPairs');

$config->workflowdatasource->methods['sys']['common']      = array('getCurrencyList', 'getCurrencySign');
$config->workflowdatasource->methods['sys']['company']     = array('getPairs');
$config->workflowdatasource->methods['sys']['entry']       = array('getPairs');
$config->workflowdatasource->methods['sys']['flow']        = array('getDataPairs');
$config->workflowdatasource->methods['sys']['group']       = array('getPairs', 'getUserPairs');
$config->workflowdatasource->methods['sys']['schema']      = array('getPairs');
$config->workflowdatasource->methods['sys']['store']       = array('getPairs');
$config->workflowdatasource->methods['sys']['tree']        = array('getPairs', 'getFamily', 'getAllChildID', 'getOptionMenu', 'getOptionMenuByMajor');
$config->workflowdatasource->methods['sys']['user']        = array('getPairs', 'getRealNamePairs', 'getUserRoles', 'getRoleList', 'getUserManagerPairs');
$config->workflowdatasource->methods['sys']['usercontact'] = array('getPairs');

$config->workflowdatasource->langList['orderStatus']      = array('app' => 'crm', 'module' => 'order',            'field' => 'statusList');
$config->workflowdatasource->langList['orderReason']      = array('app' => 'crm', 'module' => 'order',            'field' => 'closedReasonList');
$config->workflowdatasource->langList['contractType']     = array('app' => 'crm', 'module' => 'contract',         'field' => 'typeList');
$config->workflowdatasource->langList['contractDelivery'] = array('app' => 'crm', 'module' => 'contract',         'field' => 'deliveryList');
$config->workflowdatasource->langList['contractReturn']   = array('app' => 'crm', 'module' => 'contract',         'field' => 'returnList');
$config->workflowdatasource->langList['contractPay']      = array('app' => 'crm', 'module' => 'purchasecontract', 'field' => 'returnList');
$config->workflowdatasource->langList['contractStatus']   = array('app' => 'crm', 'module' => 'contract',         'field' => 'statusList');
$config->workflowdatasource->langList['customerType']     = array('app' => 'sys', 'module' => 'customer',         'field' => 'typeList');
$config->workflowdatasource->langList['customerRelation'] = array('app' => 'sys', 'module' => 'customer',         'field' => 'relationList');
$config->workflowdatasource->langList['customerSource']   = array('app' => 'sys', 'module' => 'customer',         'field' => 'sourceList');
$config->workflowdatasource->langList['customerStatus']   = array('app' => 'sys', 'module' => 'customer',         'field' => 'statusList');
$config->workflowdatasource->langList['providerStatus']   = array('app' => 'sys', 'module' => 'customer',         'field' => 'statusList');
$config->workflowdatasource->langList['contactStatus']    = array('app' => 'crm', 'module' => 'contact',          'field' => 'statusList');
$config->workflowdatasource->langList['leadsStatus']      = array('app' => 'crm', 'module' => 'leads',            'field' => 'statusList');
$config->workflowdatasource->langList['productType']      = array('app' => 'sys', 'module' => 'product',          'field' => 'typeList');
$config->workflowdatasource->langList['productModel']     = array('app' => 'sys', 'module' => 'product',          'field' => 'models');
$config->workflowdatasource->langList['productUnit']      = array('app' => 'sys', 'module' => 'product',          'field' => 'units');
$config->workflowdatasource->langList['productStatus']    = array('app' => 'sys', 'module' => 'product',          'field' => 'statusList');
$config->workflowdatasource->langList['invoiceKind']      = array('app' => 'sys', 'module' => 'invoice',          'field' => 'kindList');
$config->workflowdatasource->langList['invoiceType']      = array('app' => 'sys', 'module' => 'invoice',          'field' => 'typeList');
$config->workflowdatasource->langList['invoiceSaleType']  = array('app' => 'sys', 'module' => 'invoice',          'field' => 'saleTypeList');
$config->workflowdatasource->langList['invoiceSendway']   = array('app' => 'sys', 'module' => 'invoice',          'field' => 'sendwayList');
$config->workflowdatasource->langList['invoiceStatus']    = array('app' => 'sys', 'module' => 'invoice',          'field' => 'statusList');
$config->workflowdatasource->langList['feedbackPri']      = array('app' => 'crm', 'module' => 'feedback',         'field' => 'priList');
$config->workflowdatasource->langList['feedbackType']     = array('app' => 'crm', 'module' => 'feedback',         'field' => 'typeList');
$config->workflowdatasource->langList['feedbackReason']   = array('app' => 'crm', 'module' => 'feedback',         'field' => 'closedReasonList');
$config->workflowdatasource->langList['feedbackStatus']   = array('app' => 'crm', 'module' => 'feedback',         'field' => 'statusList');
$config->workflowdatasource->langList['public']           = array('app' => 'sys', 'module' => 'customer',         'field' => 'publicList');
$config->workflowdatasource->langList['deleted']          = array('app' => 'sys', 'module' => 'common',           'field' => 'deleteList');
$config->workflowdatasource->langList['gender']           = array('app' => 'crm', 'module' => 'common',           'field' => 'genderList');
$config->workflowdatasource->langList['role']             = array('app' => 'sys', 'module' => 'user',             'field' => 'roleList');
$config->workflowdatasource->langList['express']          = array('app' => 'sys', 'module' => 'product',          'field' => 'expresses');
$config->workflowdatasource->langList['bank']             = array('app' => 'sys', 'module' => 'common',           'field' => 'bankList');
