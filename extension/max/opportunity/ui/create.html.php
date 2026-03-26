<?php
/**
 * The create view file of opportunity module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     opportunity
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('opportunity.create');

formGridPanel
(
    on::change('[name=impact]', 'computeIndex'),
    on::change('[name=chance]', 'computeIndex'),
    set::title($lang->opportunity->create),
    set::modeSwitcher(false),
    set::fields($fields)
);
