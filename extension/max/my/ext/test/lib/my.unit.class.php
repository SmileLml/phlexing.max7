<?php
declare(strict_types=1);
/**
 * The test class file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <liyang@easycorp.ltd>
 * @package     my
 * @link        http://www.zentao.net
 */
class myTest
{
    private $objectModel;

    public function __construct()
    {
        su('admin');

        global $tester;
        $this->objectModel = $tester->loadModel('my');
    }

    /**
     * 获取审计列表。
     * Get audit list.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @access public
     * @return array
     */
    public function getAuditListTest(string $browseType, string $orderBy, int $recPerPage)
    {
        global $app;
        $app->loadClass('pager', true);
        $app->rawModule = 'my';
        $app->rawMethod = 'myaudit';
        $pager = pager::init(0, $recPerPage, 1);

        return $this->objectModel->getAuditList($browseType, $orderBy, $pager);
    }

    /**
     * 获取基线列表。
     * Get baseline list.
     *
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @access public
     * @return array
     */
    public function getBaselineListTest(string $browseType, string $orderBy, int $recPerPage)
    {
        global $app;
        $app->loadClass('pager', true);
        $app->rawModule = 'my';
        $app->rawMethod = 'baseline';
        $pager = pager::init(0, $recPerPage, 1);

        return $this->objectModel->getBaselineList($browseType, $orderBy, $pager);
    }
}
