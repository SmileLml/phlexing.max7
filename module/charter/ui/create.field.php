<?php
namespace zin;
global $lang, $config, $app;

$users    = data('users');
$products = data('products');

$fields = defineFieldList('charter.create');

$fields->field('name')
    ->required(true);

$fields->field('level')
    ->width('1/6')
    ->control('priPicker', array('required' => true))
    ->items(data('levelList'))
    ->value(data('level'));

$fields->field('category')
    ->width('1/6')
    ->control('picker', array('required' => true))
    ->items($lang->charter->categoryList)
    ->value('IPD');

$fields->field('market')
    ->width('1/6')
    ->control('picker', array('required' => true))
    ->items($lang->charter->marketList)
    ->value('domestic');

$fields->field('appliedBy')
    ->control('picker', array('required' => true))
    ->items($users)
    ->value($app->user->account);

$budgetItemList = array();
$budgetUnitList = data('budgetUnitList') ? data('budgetUnitList') : array();
foreach($budgetUnitList as $key => $value)
{
    $budgetItemList[] = array('text' => $value, 'value' => $key, 'url' => "javascript:toggleBudgetUnit('{$key}')");
}

$fields->field('budget')
    ->label($lang->charter->budget)
    ->control(
        'inputControl',
        array(
            'control' => 'input',
            'name'    => 'budget',
            'prefix'  =>
            array(
                'control' => 'dropdown',
                'name' => 'budgetUnit',
                'items' => $budgetItemList,
                'widget' => true,
                'text' => zget($lang->project->currencySymbol, 'CNY'),
                'className' => 'ghost'
            ),
            'prefixWidth' => 34
        )
    );

$fields->field('budgetUnit')->control('hidden')->value('CNY');

$fields->field('productCharterBox')
    ->width('full')
    ->control(array('control' => 'productCharterBox', 'products' => $products, 'objectType' => data('objectType')));

$fields->field('type')->control('input')->className('hidden')->value(data('objectType'));

foreach(data('fileList') as $key => $fileName)
{
    $fields->field($key)
        ->name("files[$key]")
        ->label($fileName)
        ->width('1/3')
        ->multiple(false)
        ->control('fileSelector', array('multiple' => false, 'maxFileCount' => 1));
}

$fields->field('spec')
    ->width('full')
    ->control('editor');
