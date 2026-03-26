<?php
/**
 * The model file of faq module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     faq
 * @version     $Id: model.php 5079 2013-07-10 00:44:34Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class faqModel extends model
{
    /**
     * Get frequently asked questions.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @param  int    $productIds
     * @access public
     * @return array
     */
    public function getFaqs($productID, $moduleID, $productIds)
    {
        $faqs = $this->dao->select('*')->from(TABLE_FAQ)
            ->where('1=1')
            ->andWhere('product')->in($productIds)
            ->beginIF($productID)->andWhere('product')->eq($productID)
            ->beginIF($moduleID)->andWhere('module')->eq($moduleID)
            ->fetchAll('', false);

        foreach($faqs as $faq)
        {
            $faq = $this->loadModel('file')->replaceImgURL($faq, 'answer');
            $faq->answer = $this->file->setImgSize($faq->answer);
        }

        return $faqs;
    }

    /**
     * Get id and question pairs.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getPairs($productID = 0)
    {
        return $this->dao->select('id, question')->from(TABLE_FAQ)
            ->where('1=1')
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->fetchPairs();
    }

    /**
     * Get FAQ by ID.
     *
     * @param  int    $faqID
     * @access public
     * @return array
     */
    public function getByID($faqID)
    {
        $faq = $this->dao->select('*')->from(TABLE_FAQ)->where('id')->eq($faqID)->fetch();
        if($faq) $faq = $this->loadModel('file')->replaceImgURL($faq, 'answer');

        return $faq;
    }

    /**
     * Get FAQ tree.
     *
     * @access public
     * @return array
     */
    public function getFaqTree()
    {
        $grantProducts = $this->loadModel('feedback')->getGrantProducts();
        $productPairs  = $this->dao->select('product')->from(TABLE_FAQ)->where('product')->in(array_keys($grantProducts))->fetchPairs();
        $productPairs  = array_unique($productPairs);
        $tree = array();
        foreach($productPairs as $productID)
        {
            $productTree = $this->loadModel('tree')->getProductStructure($productID, 'story');
            $tree[$productID] = $productTree;
        }

        return $tree;
    }

    /**
     * Create a FAQ.
     *
     * @access public
     * @return int|bool
     */
    public function create()
    {
        $now = helper::now();
        $faq = fixer::input('post')
            ->add('addedtime', $now)
            ->stripTags($this->config->faq->editor->create['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();
        $faq = $this->loadModel('file')->processImgURL($faq, $this->config->faq->editor->create['id'], $this->post->uid);

        $this->dao->insert(TABLE_FAQ)->data($faq)->autocheck()->batchCheck($this->config->faq->create->requiredFields, 'notempty')->exec();
        if(!dao::isError())
        {
            $faqID = $this->dao->lastInsertID();
            return $faqID;
        }
        return false;
    }

    /**
     * Update a FAQ.
     *
     * @param  int    $faqID
     * @access public
     * @return void
     */
    public function update($faqID)
    {
        $faq = fixer::input('post')
            ->stripTags($this->config->faq->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();
        $faq = $this->loadModel('file')->processImgURL($faq, $this->config->faq->editor->edit['id'], $this->post->uid);

        $this->dao->update(TABLE_FAQ)->data($faq)->where('id')->eq($faqID)->autocheck()->batchCheck($this->config->faq->edit->requiredFields, 'notempty')->exec();
    }

    /**
     * Print child module.
     *
     * @param  array  $module
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function printChildModule($module = array(), $moduleID = 0)
    {
        foreach($module as $childModule)
        {
            $active = '';
            if($moduleID == $childModule->id) $active = "class='active'";
            echo '<ul>';
            echo "<li $active>";
            echo html::a(helper::createLink('faq', 'browse', "productID=$childModule->root&moduleID=$childModule->id"), $childModule->name, '', "class='text-ellipsis' title='{$childModule->name}'");
            if(isset($childModule->children)) $this->printChildModule($childModule->children, $moduleID);
            echo '</li>';
            echo '</ul>';
        }
    }

    /**
     * Get product pairs.
     *
     * @access public
     * @return array
     */
    public function getProductPairs()
    {
        return $this->dao->select('*')->from(TABLE_FEEDBACKVIEW)->where('account')->eq($this->app->user->account)->fetchPairs('product', 'product');
    }
}
