<?php
/**
 * The review file of charter module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     charter
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('vision', $config->vision);

$fields = useFields('charter.review');

$formActions[] = 'submit';
if(!empty($currentNode->priv))
{
    if(in_array('revert', $currentNode->priv))  $formActions[] = array('text' => $lang->approval->revert,  'url' => createLink('approval', 'revert', "objectType=charter&objectID={$charter->id}"), 'innerClass' => 'revert-btn',  'data-toggle' => 'modal', 'data-hide-others' => 'true');
    if(in_array('forward', $currentNode->priv)) $formActions[] = array('text' => $lang->approval->forward, 'url' => createLink('approval', 'forward', "objectType=charter&objectID={$charter->id}"), 'innerClass' => 'revert-btn',  'data-toggle' => 'modal', 'data-hide-others' => 'true');
    if(in_array('addnode', $currentNode->priv)) $formActions[] = array('text' => $lang->approval->addNode, 'url' => createLink('approval', 'addNode', "objectType=charter&objectID={$charter->id}"), 'innerClass' => 'revert-btn',  'data-toggle' => 'modal', 'data-hide-others' => 'true');
}

modalHeader();
formPanel
(
    set::size('normal'),
    set::fields($fields),
    set::actions($formActions)
);
history();
