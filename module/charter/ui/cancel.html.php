<?php
/**
 * The cancel view file of charter module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu<hufangzhou@easycorp.ltd>
 * @package     charter
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('vision', $config->vision);

modalHeader(set::title($lang->charter->cancel));
formPanel
(
    set::submitBtnText($lang->cancel),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows('5')
        )
    )
);
hr();
history();

render();
