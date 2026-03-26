<?php
namespace zin;
global $lang;
$story = data('story');
if(!empty($story->feedback))
{
    $link = a(set::href(createLink('feedback', 'adminview', "feedbackID={$story->feedback}")), " #$story->feedback $story->feedbackTitle");
    $html = $lang->story->sourceList[$story->source] . ' ' . $story->sourceNote . $link;
    query('#sourceBox')->replaceWith($html);
}
