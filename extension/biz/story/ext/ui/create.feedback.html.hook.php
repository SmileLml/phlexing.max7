<?php
namespace zin;
$fromType = data('fromType');
if($fromType == 'feedback')
{
    data('activeMenuID', 'browse');
    $feedback = data('fromObject');
    query('formGridPanel')->each(function($node) use($feedback)
    {
        $fields = $node->prop('fields');

        $fields->field('feedback')->hidden()->value($feedback->id);
        $fields->field('feedbackBy')->readonly()->value($feedback->feedbackBy);
        $fields->field('notifyEmail')->readonly()->value($feedback->notifyEmail);

        $node->setProp('fields', $fields);
    });
}

if($fromType == 'ticket')
{
    data('activeMenuID', 'ticket');
    query('formGridPanel')->each(function($node)
    {
        $fields = $node->prop('fields');

        $fields->field('ticket')->hidden()->value(data('fromObject')->id);

        $node->setProp('fields', $fields);
    });
}
