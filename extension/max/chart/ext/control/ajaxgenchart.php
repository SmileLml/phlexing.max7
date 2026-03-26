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
     * Ajax gen chart.
     *
     * @access public
     * @return void
     */
    public function ajaxGenChart()
    {
        $dataset      = $this->post->dataset;
        $type         = $this->post->type;
        $settings     = $this->post->settings;
        $filterValues = $this->post->filterValues ? $this->post->filterValues : array();

        $filter = isset($settings['filter']) ? $settings['filter'] : array();
        $group  = isset($settings['group'])  ? $settings['group']  : array();

        /* Line data must be ordered by time. */
        if($type == 'line')
        {
            $order = array(array('value' => $settings['xaxis'][0]['field'], 'sort' => 'asc'));
        }
        else
        {
            $order = isset($settings['order']) ? $settings['order'] : array();
        }

        if(strpos($type, 'Report') === false)
        {
            $table = $this->chart->getTableInfo($dataset);
            $rows  = $this->chart->getData($table->schema, $filter, $filterValues, $group, $order, 1000);
        }

        $users = $this->loadModel('user')->getPairs('noletter');
        switch($type)
        {
            case 'table':
                $data = $this->chart->genTable($dataset, $settings, $rows, $users);
                break;
            case 'line':
                $data = $this->chart->genLine($dataset, $settings, $rows, $users);
                break;
            case 'bar':
                $data = $this->chart->genBar($dataset, $settings, $rows, $users);
                break;
            case 'pie':
                $data = $this->chart->genPie($dataset, $settings, $rows, $users);
                break;
            case 'testingReport':
            case 'buildTestingReport':
                if(!$filterValues['build.id'])
                {
                    $filters = array('project' => array(), 'execution' => array(), 'build' => array());
                    list($sysOptions, $defaults) = $this->loadModel('dataset')->getSysOptions($filters);

                    $filterValues['project.id']   = $defaults['project'];
                    $filterValues['execution.id'] = $defaults['execution'];
                    $filterValues['build.id']     = $defaults['build'];
                }
                $data = $this->chart->genTestingReport($type, $filterValues);
                break;
            case 'executionTestingReport':
                if(!$filterValues['execution.id'])
                {
                    $filters = array('project' => array(), 'execution' => array());
                    list($sysOptions, $defaults) = $this->loadModel('dataset')->getSysOptions($filters);

                    $filterValues['project.id']   = $defaults['project'];
                    $filterValues['execution.id'] = $defaults['execution'];
                }
                $data = $this->chart->genTestingReport($type, $filterValues);
                break;
            case 'projectTestingReport':
                if(!$filterValues['project.id'])
                {
                    $filters = array('project' => array());
                    list($sysOptions, $defaults) = $this->loadModel('dataset')->getSysOptions($filters);

                    $filterValues['project.id']   = $defaults['project'];
                }
                $data = $this->chart->genTestingReport($type, $filterValues);
                break;
            case 'dailyTestingReport':
                if(!$filterValues['build.id'])
                {
                    $filters = array('project' => array(), 'execution' => array(), 'build' => array());
                    list($sysOptions, $defaults) = $this->loadModel('dataset')->getSysOptions($filters);

                    $filterValues['project.id']   = $defaults['project'];
                    $filterValues['execution.id'] = $defaults['execution'];
                    $filterValues['build.id']     = $defaults['build'];
                }
                $data = $this->chart->genTestingReport($type, $filterValues);
                break;
        }

        echo json_encode($data);
    }
}
