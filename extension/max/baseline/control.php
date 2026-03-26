<?php
/**
 * The control file of baseline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     baseline
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class baseline extends control
{
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('doc');
    }

    /**
     * Browse templates.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function template($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title       = $this->lang->baseline->template;
        $this->view->posisiton[] = $this->lang->baseline->template;
        $this->view->templates   = $this->baseline->getList($orderBy, $pager);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->display();
    }

    /**
     * Creata a template.
     *
     * @access public
     * @return void
     */
    public function createTemplate()
    {
        if($_POST)
        {
            $result = $this->baseline->create();

            if(!$result)
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('template');
            return $this->send($response);
        }

        $this->view->title      = $this->lang->baseline->createTemplate;
        $this->view->position[] = $this->lang->baseline->createTemplate;
        $this->display();
    }

    /**
     * Creata a template.
     *
     * @param  int $templateID
     * @access public
     * @return void
     */
    public function editTemplate($templateID = 0)
    {
        if($_POST)
        {
            $result = $this->baseline->update($templateID);

            if(!$result)
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $doc = $this->baseline->getByID($templateID);
            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = ($doc->type == 'chapter' || $doc->type == 'article') ? inlink('view', "id=$doc->template") : inlink('template');
            return $this->send($response);
        }

        $this->view->title      = $this->lang->baseline->editTemplate;
        $this->view->position[] = $this->lang->baseline->editTemplate;
        $this->view->template   = $this->baseline->getByID($templateID);
        $this->display();
    }

    /**
     * Edit book of template.
     *
     * @param  int $nodeID
     * @access public
     * @return void
     */
    public function editBook($nodeID = 0)
    {
        $node = $this->baseline->getByID($nodeID);

        $this->view->title      = $this->lang->baseline->editBook;
        $this->view->position[] = $this->lang->baseline->editBook;
        $this->view->node       = $node;
        $this->view->optionMenu = $this->doc->getBookOptionMenu($node->template, false, $node->id);
        $this->display();
    }

    /**
     * Delete template.
     *
     * @param  int     $templateID
     * @param  varchar $confirm
     * @access public
     * @return void
     */
    public function delete($templateID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->baseline->confirmDelete, $this->createLink('baseline', 'delete', "templateID=$templateID&confirm=yes"), '');
            exit;
        }
        else
        {
            $this->dao->delete()->from(TABLE_DOC)->where('id')->eq($templateID)->exec();
            $this->dao->delete()->from(TABLE_DOCCONTENT)->where('doc')->eq($templateID)->exec();

            die(js::reload('parent'));
        }
    }

    /**
     * View a template.
     *
     * @param  int $templateID
     * @param  int $nodeID
     * @access public
     * @return void
     */
    public function view($templateID = 0, $nodeID = 0)
    {
        $template = $this->baseline->getByID($templateID);
        $bookID   = $template->template ? $template->template : $template->id;

        if($template->type == 'book' || $template->template)
        {
            $this->view->catalog = $this->baseline->getCatalog($bookID, $nodeID, $this->doc->computeSN($bookID, 'baseline'));
        }

        $template = $this->baseline->getByID($templateID);
        if($template->type  == 'markdown') $template->content = commonModel::processMarkdown($template->content);

        $this->view->title      = $this->lang->baseline->view;
        $this->view->position[] = $this->lang->baseline->view;
        $this->view->template   = $template;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $this->display();
    }

    /**
     * Manage a book.
     *
     * @param  int $templateID
     * @param  int $nodeID
     * @access public
     * @return void
     */
    public function manageBook($templateID = 0, $nodeID = 0)
    {
        if($_POST)
        {
            $result = $this->baseline->manageBook($templateID, $nodeID);
            if($result) return $this->send(array('result' => 'success', 'message'=>$this->lang->saveSuccess, 'locate' => $this->createLink('baseline', 'view', "templateID=$templateID&nodeID=0") . "#node" . $nodeID));
            return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        $this->view->title      = $this->lang->baseline->manageBook;
        $this->view->position[] = $this->lang->baseline->manageBook;
        $this->view->template   = $this->baseline->getByID($templateID);
        $this->view->node       = $this->baseline->getByID($nodeID);
        $this->view->children   = $this->baseline->getChildren($templateID, $nodeID);

        $this->display();
    }

    /**
    *  Ajax get templates.
    *
    *  @param  string $type
    *  @access public
    *  @return void
    */
    public function ajaxGetTemplates($type = '')
    {
        $items     = array();
        $templates = $this->baseline->getPairsByType($type);
        foreach($templates as $key => $value) $items[] = array('value' => $key, 'text' => $value);
        return print(json_encode($items));
    }

    /**
    *  Ajax get documents.
    *
    *  @param  string $template
    *  @param  string $from
    *  @param  int    $project
    *  @param  string $contentType
    *  @param  string $viewType
    *  @access public
    *  @return void
    */
    public function ajaxGetDocs($template = '', $from = 'review', $project = 0, $contentType = '', $viewType = 'html')
    {
        $templates = $this->baseline->getPairsByTemplate($template, $from, $project, $contentType);
        if($viewType == 'json')
        {
            $items = array();
            foreach($templates as $key => $value) $items[] = array('value' => $key, 'text' => $value);
            return print(json_encode($items));
        }
        die(html::select('doc', arrayUnion(array('' => ''), $templates), '', "class='form-control chosen'"));
    }

    /**
     * Ajax get content.
     *
     * @param  int    $templateID
     * @access public
     * @return void
     */
    public function ajaxGetContent($templateID)
    {
        $template = $this->baseline->getByID($templateID);
        die(json_encode($template));
    }

    /**
     * Template type.
     *
     * @access public
     * @return void
     */
    public function templateType()
    {
        echo $this->fetch('custom', 'set', 'module=baseline&field=objectList');
    }
}
