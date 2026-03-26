<?php
/**
 * The control file of calendar module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     calendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class company extends control
{
    /**
     * Todo.
     *
     * @param  int    $parent
     * @param  string $begin
     * @param  string $end
     * @param  string $iframe  yes|no
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function todo($parent = 0, $begin = 0, $end = 0, $iframe = 'no', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        $super = common::hasPriv('company', 'alltodo');
        if(!$super)
        {
            $currentDept = $this->app->user->dept;
            if(empty($parent)) $parent = $currentDept;
            if($parent != $currentDept)
            {
                $dept = $this->loadModel('dept')->getById($parent);
                if(strpos($dept->path, ",{$currentDept},") === false) $parent = $currentDept;
            }
        }

        $mainDepts = $this->loadModel('dept')->getOptionMenu($super ? 0 : $this->app->user->dept);
        unset($mainDepts[0]);
        if($super) $mainDepts = arrayUnion(array('all' => '/' . $this->lang->company->allDept), $mainDepts);

        if($iframe == 'yes')
        {
            $this->app->loadClass('pager', $static = true);
            $pager = new pager($recTotal, $recPerPage, $pageID);
            $days  = array();
            for($i = $begin; $i <= $end; $i += 86400) $days[] = date('Y-m-d', $i);

            $begin = date('Y-m-d', $begin);
            $end   = date('Y-m-d', $end);

            $this->view->datas     = $this->company->getTodo($parent, $begin, $end, $pager);
            $this->view->parent    = $parent;
            $this->view->depts     = $mainDepts;
            $this->view->users     = $this->loadModel('user')->getPairs('noletter|noclosed|nodeleted');
            $this->view->iframe    = $iframe;
            $this->view->begin     = $begin;
            $this->view->end       = $end;
            $this->view->pager     = $pager;
            $this->view->days      = $days;
            die($this->display());
        }

        if(!empty($_POST))
        {
            $begin = explode('-', $this->post->begin);
            $begin = implode('', $begin);
            $end   = explode('-', $this->post->end);
            $end   = implode('', $end);
            die(js::locate(inlink('todo', "dept={$this->post->dept}&begin=$begin&end=$end&iframe=no&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID"), 'parent'));
        }

        $begin = date('Y-m-d', $begin == 0 ? time() : strtotime($begin));
        $end   = date('Y-m-d', $end   == 0 ? strtotime('+1 week') : strtotime($end));

        $this->view->title      = $this->lang->company->todo;
        $this->view->parent     = $parent;
        $this->view->begin      = $begin;
        $this->view->end        = $end;
        $this->view->mainDepts  = $mainDepts;
        $this->view->iframe     = $iframe;
        $this->view->recTotal   = $recTotal;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pageID;
        $this->display();
    }
}
