<?php
/**
 * The change file of demand module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     demand
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

$fields = useFields('demand.change');

jsVar('lastReviewer', explode(',', $lastReviewer));

modalHeader();
formPanel
(
    on::change('[name=needNotReview]', 'changeNeedReview'),
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    set::size('normal'),
    set::actions(array
    (
        array('text' => $lang->save,               'data-status' => 'active', 'class' => 'primary',   'btnType' => 'submit'),
        array('text' => $lang->story->doNotSubmit, 'data-status' => 'draft',  'class' => 'secondary', 'btnType' => 'submit'),
        array('text' => $lang->goback,             'back' => true)
    )),
    set::fields($fields)
);
