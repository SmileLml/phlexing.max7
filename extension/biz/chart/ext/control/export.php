<?php
/**
 * The control file of chart module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     chart
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class chart extends control
{
    /**
     * Export Charts.
     *
     * @param  string $chartID
     * @param  string $mode design|preview
     * @access public
     * @return void
     */
    public function export($chartID = '', $mode = 'design')
    {
        $this->app->loadLang('report');
        if($_POST)
        {
            if(empty($_POST['fileName'])) return;
            $items = explode(',', trim($this->post->items, ','));
            foreach($items as $item)
            {
                $chartData = explode('_', $item);
                $chartID   = $chartData[1];

                $chart = $this->chart->getByID($chartID);
                if(empty($chart)) continue;

                $datas[$item]  = array('title' => array('text' => $chart->name));
                $images[$item] = isset($_POST[$item]) ? $this->post->$item : '';
                unset($_POST[$item]);
            }
            $this->post->set('datas',  $datas);
            $this->post->set('items',  $items);
            $this->post->set('images', $images);
            $this->post->set('kind',   'chart');
            $this->fetch('file', 'exportBIChart', $_POST);
        }

        $this->app->loadLang('file');
        $this->view->emptyLang = sprintf($this->lang->error->notempty, $this->lang->file->fileName);
        $this->view->chartID   = $chartID;
        $this->view->mode      = $mode;
        $this->display();
    }
}
