<?php
/**
 * The terminal view file of instance module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     instance
 * @link        https://www.zentao.net
 */
namespace zin;
set::zui(true);

jsVar('webSocketURL', $webSocketURL);
jsVar('instanceLang', $this->lang->instance);

div(h::importCss($app->getWebRoot() . 'js/xterm/xterm.css'), setID('terminal'), setClass('px-1 mt-2 w-full h-full'));

render('pagebase');
