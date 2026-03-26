<?php
/**
 * The control file of faq module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     faq
 * @version     $Id: control.php 5079 2013-07-10 00:44:34Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class faq extends control
{
    /**
     * Browse FAQ list.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $moduleID = 0)
    {
        $sourceVision = $this->config->vision;
        $this->config->vision = 'rnd';

        $products = $this->loadModel('feedback')->getGrantProducts();

        $this->view->title          = $this->lang->faq->faq;
        $this->view->position[]     = $this->lang->faq->faq;
        $this->view->products       = $products;
        $this->view->moduleTree     = $this->faq->getFaqTree($moduleID);
        $this->view->currentModule  = $moduleID;
        $this->view->currentProduct = $productID;
        $this->view->faqs           = $this->faq->getFaqs($productID, $moduleID, array_keys($products));

        $this->config->vision = $sourceVision;
        $this->display();
    }

    /**
     * Create a FAQ.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function create($productID = 0, $moduleID = 0)
    {
        if($_POST)
        {
            $response['result']  = 'success';
            $response['message'] = '';
            $faqID = $this->faq->create();

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $response['message'] = $this->lang->saveSuccess;
            if(isOnlybody())
            {
                $response['locate']  = 'parent';
                return $this->send($response);
            }

            $response['locate'] = $this->createLink('faq', 'browse');
            return $this->send($response);
        }

        $this->view->title = $this->lang->faq->create;

        $this->view->products  = $this->loadModel('feedback')->getGrantProducts();
        $this->view->moduleID  = $moduleID;
        $this->view->productID = $productID;
        $this->display();
    }

    /**
     * Edit a FAQ.
     *
     * @param  int    $faqID
     * @access public
     * @return void
     */
    public function edit($faqID)
    {
        if($_POST)
        {
            $response['result']  = 'success';
            $response['message'] = '';
            $this->faq->update($faqID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $response['message'] = $this->lang->saveSuccess;
            if(isOnlybody())
            {
                $response['locate']  = 'parent';
                return $this->send($response);
            }

            $response['locate'] = $this->createLink('faq', 'browse');
            return $this->send($response);
        }

        $this->view->products = $this->loadModel('feedback')->getGrantProducts();
        $this->view->faq      = $this->faq->getByID($faqID);
        $this->display();
    }

    /**
     * Delete a FAQ.
     *
     * @param  int    $faqID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete($faqID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->faq->confirmDelete, inlink('delete', "faqID=$faqID&confirm=yes")));
        }
        else
        {
            $this->dao->delete()->from(TABLE_FAQ)->where('id')->eq($faqID)->exec();
            if(!dao::isError()) die(js::reload('parent'));
        }
    }

    /**
     * Get modules.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxGetModules($productID = 0)
    {
        $modules = $this->loadModel('tree')->getOptionMenu($productID, 'story', 0, 'all');
        die(html::select('module', $modules, '', "class='form-control chosen'"));
    }

    /**
     * Get answer.
     *
     * @param  int    $faqID
     * @access public
     * @return void
     */
    public function ajaxGetAnswer($faqID = 0)
    {
        if($faqID == 0) die;
        $faq = $this->faq->getByID($faqID);
        die($faq->answer);
    }
}
