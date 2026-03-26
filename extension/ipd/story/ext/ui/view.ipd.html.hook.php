<?php
namespace zin;

$context = \zin\context();
extract($context->data);

global $config;

/* IPD研发界面查看OR界面的业用需求，OR界面查看研发需求，隐藏页面所有链接/操作按钮。 */
if(($config->vision != 'or' && $story->vision == 'or') || ($story->type == 'story' && $config->vision == 'or'))
{
    query('.detail-actions')->remove();
    query('#linkButton')->remove();
    query('.tab-content')->find('a')->addClass('disabled');
}

