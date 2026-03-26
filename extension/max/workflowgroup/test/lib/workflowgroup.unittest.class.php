<?php
declare(strict_types=1);
/**
 * The unittest file of workflowgroup module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     workflowgroup
 * @link        https://www.zentao.net
 */
class workflowgroupTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('workflowgroup');
    }

    /**
     * 测试获取一个流程组。
     * Test get a workflow group.
     *
     * @param  int $id
     * @access public
     * @return object|false
     */
    public function getByIdTest(int $id): object|bool
    {
        return $this->objectModel->getByID($id);
    }

    /**
     * 测试创建一个流程组。
     * Test create a workflow group.
     *
     * @param  object $group
     * @access public
     * @return array|object
     */
    public function createTest(object $group): array|object
    {
        $groupID = $this->objectModel->create($group);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($groupID);
    }

    /**
     * 测试更新一个流程组。
     * Test update a workflow group.
     *
     * @param  object $group
     * @param  object $oldGroup
     * @access public
     * @return array
     */
    public function updateTest(object $group, object $oldGroup): array
    {
        $changes = $this->objectModel->update($group, $oldGroup);
        if(dao::isError()) return dao::getError();

        return $changes;
    }
}
