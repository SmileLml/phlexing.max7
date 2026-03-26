<?php
$config->file->libreOfficeTurnon = 0;
$config->file->sofficePath       = '';

if(isset($lang->excel))
{
    $config->excel->editor['feedback'] = array('desc');
    $config->excel->freeze->feedback   = 'title';
    $config->excel->noAutoFilter[] = 'feedback';

    $config->excel->editor['ticket'] = array('desc', 'resolution');
    $config->excel->freeze->ticket   = 'title';
}

$config->file->allAllowExtensions = array('jpeg', 'jpg', 'gif', 'png', '.bmp', "flv", "swf", "mkv", "avi", "rm", "rmvb", "mpeg", "mpg", "ogg", "ogv", "mov", "wmv", "mp4", "webm", "mp3", "wav", "mid", 'doc', 'docx', 'dot', 'wps', 'wri', 'pdf', 'ppt', 'pptx', 'xls', 'xlsx', 'ett', 'xlt', 'xlsm', 'csv');

/* wiki config */
$config->word->wiki = new StdClass();
$config->word->wiki->exportFields[] = 'title';
$config->word->wiki->exportFields[] = 'content';
$config->word->wiki->exportFields[] = 'files';

$config->word->wiki->style['title']   = 'title';
$config->word->wiki->style['content'] = 'showImage';

/* product config */
$config->word->product = new StdClass();
$config->word->product->exportFields[] = 'title';
$config->word->product->exportFields[] = 'content';
$config->word->product->exportFields[] = 'files';

$config->word->product->style['title']   = 'title';
$config->word->product->style['content'] = 'showImage';

/* project config */
$config->word->project = new StdClass();
$config->word->project->exportFields[] = 'title';
$config->word->project->exportFields[] = 'content';
$config->word->project->exportFields[] = 'files';

$config->word->project->style['title']   = 'title';
$config->word->project->style['content'] = 'showImage';

/* execution config */
$config->word->execution = new StdClass();
$config->word->execution->exportFields[] = 'title';
$config->word->execution->exportFields[] = 'content';
$config->word->execution->exportFields[] = 'files';

$config->word->execution->style['title']   = 'title';
$config->word->execution->style['content'] = 'showImage';

/* custom config */
$config->word->custom = new StdClass();
$config->word->custom->exportFields[] = 'title';
$config->word->custom->exportFields[] = 'content';
$config->word->custom->exportFields[] = 'files';

$config->word->custom->style['title']   = 'title';
$config->word->custom->style['content'] = 'showImage';

$config->word->api = new StdClass();
$config->word->api->exportFields[] = 'title';
$config->word->api->exportFields[] = 'content';

$config->word->api->style['title']   = 'title';
$config->word->api->style['content'] = 'showImage';

$config->word->mine = new StdClass();
$config->word->mine->exportFields[] = 'title';
$config->word->mine->exportFields[] = 'content';
$config->word->mine->exportFields[] = 'files';

$config->word->mine->style['title']   = 'title';
$config->word->mine->style['content'] = 'showImage';

$config->word->size = new stdclass();
$config->word->size->titles[1] = 20;
$config->word->size->titles[2] = 18;
$config->word->size->titles[3] = 16;
$config->word->size->titles[4] = 14;

$config->word->imageExtension = array('png', 'jpg', 'gif', 'jpeg');

$config->file->docExportType = 'text,word,ppt,excel,url,article,attachment';
$config->file->docFileType   = 'text,word,ppt,excel,url,attachment';
