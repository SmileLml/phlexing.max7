<?php
/**
 * The review view file of demand module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tang Hucheng<tanghucheng@easycorp.ltd>
 * @package     demand
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

jsVar('lastReviewer', explode(',', $lastReviewer));

$fields = defineFieldList('demand.submitreview');

$fields->field('reviewBox')
    ->required(true)
    ->label($lang->demand->reviewer)
    ->control('inputGroup')
    ->itemBegin('reviewer')
    ->control('picker')
    ->disabled(data('needReview') ? true : false)
    ->multiple(true)
    ->items($reviewers)
    ->value($demand->reviewer)->itemEnd()
    ->itemBegin('needNotReview')
    ->control('checkbox')
    ->hidden($app->control->demand->checkForceReview())
    ->rootClass('center w-32')
    ->checked(data('needReview'))
    ->text($lang->demand->needNotReview)->itemEnd();

modalHeader(set::title($lang->demand->submitReview));

formPanel
(
    set::fields($fields),
    on::change('[name=needNotReview]', 'changeNeedReview')
);

history();
