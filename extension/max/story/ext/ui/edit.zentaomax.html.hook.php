<?php
namespace zin;

global $lang, $app;

$reportPairs = data('reportPairs');

jsVar('reportPairs', $reportPairs);
jsVar('sourceNoteLang', $lang->story->sourceNote);
jsVar('reportLang', $lang->story->researchreport);

$reportPicker = picker
(
    set::name('sourceNote'),
    set::items($reportPairs),
    set::value(data('story.sourceNote'))
);

if(data('story.source') == 'researchreport')
{
    query('#sourceNote')->closest('tr')->find('th')->text($lang->story->researchreport);
    query('#sourceNote')->replaceWith($reportPicker);
}

query('.sourceBox')->find('.picker-box')->on('change', jsCallback()->call('changeSource'));
