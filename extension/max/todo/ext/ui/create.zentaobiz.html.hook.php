<?php
namespace zin;

$feedback = data('feedback');
jsVar('feedback', !empty($feedback) ? $feedback : '');
if(!empty($feedback))
{
    query('formGroup.typeBox')->find('picker')->prop('value', 'feedback');
    query('#cycle')->closest('div.input-group-addon')->remove();
}
