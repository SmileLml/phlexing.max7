<?php
namespace zin;

$story = data('story');

if($story->type != 'story')
{
    query('#product')->prop('disabled', !in_array($story->stage, array('wait', 'inroadmap', 'incharter')));
    query('#branch')->prop('disabled', !in_array($story->stage, array('wait', 'inroadmap', 'incharter')));
}
