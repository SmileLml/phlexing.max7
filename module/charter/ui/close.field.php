<?php
namespace zin;

global $lang, $app;

$fields = defineFieldList('charter.close');

$closeReason = data('charter.status') == 'canceled' ? 'canceled' : 'done';
$fields->field('closedReason')->control('picker')->items($lang->charter->closeReasonList)->width('1/2')->value($closeReason)->hidden();
$fields->field('comment')->label($lang->comment)->control('editor');
