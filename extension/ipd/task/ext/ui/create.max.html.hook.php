<?php
namespace zin;

$features = data('features');
if(data('execution.type') == 'stage')
{
    query('formGridPanel')->each(function($node) use($features)
    {
        $fields = $node->prop('fields');

        $fields->field('design')
               ->foldable()
               ->control('picker')
               ->items(array());

        if(!empty($features['story']))
        {
            $fields->field('mailto')->className('lite:w-full');
            $fields->field('name')->className('full:w-full');
        }
        if(empty($features['story'])) $fields->field('name')->remove('className')->className('full:w-1/2');

        $fields->orders('type,testStoryBox', 'type,testStoryBox,parent,assignedToBox', 'desc,module,storyBox,design,keywords,mailto,files');
        $fields->fullModeOrders('type,module,storyBox,testStoryBox,parent,assignedToBox,name', 'design,desc,files,mailto,keywords');
        if(empty($features['story'])) $fields->fullModeOrders('type,module,storyBox,testStoryBox,design,assignedToBox', 'desc,files,mailto,keywords', 'design,name,assignedToBox');

        $node->setProp('fields', $fields);
    });
}
