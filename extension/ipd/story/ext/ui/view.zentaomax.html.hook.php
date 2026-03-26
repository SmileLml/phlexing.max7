<?php
namespace zin;

global $lang;

$story = data('story');
$story->sourceNote       = $story->source == 'researchreport' ? zget(data('reportPairs'), $story->sourceNote, '') : $story->sourceNote;
$lang->story->sourceNote = $story->source == 'researchreport' ? $lang->story->researchreport : $lang->story->sourceNote;
