<?php
namespace zin;
global $lang;

$task = data('task');
query('detail')->each(function($node) use($task, $lang)
{
    $sections = $node->prop('sections');

    /* 如果有附件列表字段就将相关文档放在附件列表字段上面，没有附件列表就将相关文档追加在最后面。 */
    $hasFiles    = false;
    $newSections = array();
    foreach($sections as $section)
    {
        if(zget($section, 'control') == 'fileList')
        {
            $newSections[] = setting()
                ->title($lang->task->docs)
                ->control('doclist')
                ->data($task)
                ->mode('view');
            $hasFiles = true;
        }
        $newSections[] = $section;
    }

    if(!$hasFiles)
    {
        $newSections[] = setting()
            ->title($lang->task->docs)
            ->control('doclist')
            ->data($task)
            ->mode('view');
        $hasFiles = true;
    }

    $node->setProp('sections', $newSections);
});
