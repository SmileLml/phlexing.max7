<?php
namespace zin;
global $lang, $config, $app;

$users    = data('users');
$products = data('products');
$charter  = data('charter');

$fields = defineFieldList('charter.edit');

$fields->field('name')->required(true)->value(data('charter.name'));

$fields->field('level')
    ->width('1/6')
    ->control('priPicker', array('required' => true))
    ->items(data('levelList'))
    ->value(data('level'));

$fields->field('category')
    ->width('1/6')
    ->control('picker', array('required' => true))
    ->items($lang->charter->categoryList)
    ->value(data('charter.category'));

$fields->field('market')
    ->width('1/6')
    ->control('picker', array('required' => true))
    ->items($lang->charter->marketList)
    ->value(data('charter.market'));

$fields->field('appliedBy')
    ->control('picker', array('required' => true))
    ->items($users)
    ->value(data('charter.appliedBy'));

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
            'value'   => data('charter.budget'),
            'prefix'  =>
            array(
                'control' => 'dropdown',
                'name' => 'budgetUnit',
                'items' => $budgetItemList,
                'widget' => true,
                'text' => zget($lang->project->currencySymbol, data('charter.budgetUnit')),
                'className' => 'ghost'
            ),
            'prefixWidth' => 34
        )
    );

$fields->field('budgetUnit')->control('hidden')->value(data('charter.budgetUnit'));

$fields->field('productCharterBox')
    ->width('full')
    ->control(array('control' => 'productCharterBox', 'products' => $products, 'charter' => $charter, 'objectType' => data('objectType')));

$fields->field('type')->control('input')->className('hidden')->value(data('objectType'));

foreach(data('fileList') as $key => $fileName)
{
    $fields->field($key)
        ->name("files[$key]")
        ->label($fileName)
        ->width('1/3')
        ->multiple(false)
        ->control('fileSelector', array('multiple' => false, 'maxFileCount' => 1, 'defaultFiles' => $charter->files, 'extra' => "projectApproval-$key", 'renameBtn' => false, 'deleteName' => 'deleteFiles'));
}

$fields->field('spec')
    ->width('full')
    ->control('editor')
    ->value(htmlspecialchars_decode(data('charter.spec')));
