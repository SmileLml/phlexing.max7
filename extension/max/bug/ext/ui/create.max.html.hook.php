<?php
namespace zin;

global $lang;
$injectionList = data('injectionList');
$identifyList  = data('identifyList');
query('formGridPanel')->each(function($node) use($lang, $injectionList, $identifyList)
{
     $fields = $node->prop('fields');

     $fields->field('injection')
         ->foldable()
         ->control('picker')
         ->items($injectionList);

     $fields->field('identify')
         ->foldable()
         ->control('picker')
         ->items($identifyList);

     $fields->orders('task,injection,identify');
     $node->setProp('fields', $fields);
});
