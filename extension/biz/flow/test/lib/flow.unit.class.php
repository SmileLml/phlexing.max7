<?php
declare(strict_types=1);
/**
 * The test class file of flow module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <liyang@easycorp.ltd>
 * @package     flow
 * @link        http://www.zentao.net
 */
class flowTest
{
    private $objectModel;

    public function __construct()
    {
        su('admin');

        global $tester;
        $this->objectModel = $tester->loadModel('flow');
    }

    /**
     * Create category manage link.
     *
     * @param  int    $moduleID
     * @access public
     * @return object
     */
    public function createCategoryLinkTest(int $moduleID)
    {
        $category = $this->objectModel->dao->select('*')->from(TABLE_MODULE)->where('id')->eq($moduleID)->fetch();
        return $this->objectModel->createCategoryLink($category->type, $category);
    }

    /**
     * Get category menu settings of a flow.
     *
     * @param  string $flow
     * @param  string $mode
     * @access public
     * @return array
     */
    public function getCategoriesTest(string $flow, string $mode)
    {
        return $this->objectModel->getCategories($flow, $mode);
    }
}
