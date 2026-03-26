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
     * Ajax get options.
     *
     * @param  string $field
     * @param  int    $val
     * @param  string $filters
     * @access public
     * @return void
     */
    public function ajaxGetOptions($field, $val, $filters)
    {
        $filters = explode(',', $filters);

        $allOptions = array();
        $defaults   = array();
        switch($field)
        {
            case 'project.id':
            case 'project':
                $options = array();

                $this->loadModel('execution');
                $executions    = array();
                $projectIdList = explode(',', $val);
                foreach($projectIdList as $projectID)
                {
                    $es = $this->execution->getPairs($projectID);
                    foreach($es as $k => $e) $executions[$k] = $e;
                }

                foreach($executions as $key => $execution) $options[] = array('value' => (string)$key, 'label' => $execution);

                //$defaults['execution']   = count($executions) > 0 ? (string)$this->execution->saveState(0, $executions) : '';
                $defaults['execution']   = '';
                $allOptions['execution'] = $options;
            case 'execution.id':
            case 'execution':
                $options = array();
                $executionIdList = $field == 'execution.id' ? $val : $defaults['execution'];

                $this->loadModel('build');
                $builds = array();
                foreach(explode(',', $executionIdList) as $executionID)
                {
                    $bs = $this->build->getExecutionBuilds($executionID);
                    foreach($bs as $b) $builds[$b->id] = $b->name;
                }
                foreach($builds as $key => $build) $options[] = array('value' => (string)$key, 'label' => $build);

                //$defaults['build']   = (string)key($builds);
                $defaults['build']   = '';
                $allOptions['build'] = $options;
            case 'build.id':
            case 'build':
                $options = array();
                $buildIdList = $field == 'build.id' ? $val : $defaults['build'];
                $modules = array();
                $testtasks   = array();

                if(in_array('build', $filters) or in_array('build.id', $filters))
                {
                    if(!empty($buildIdList)) $testtasks = $this->dao->select('id')->from(TABLE_TESTTASK)->where('build')->in($buildIdList)->fetchAll('id');
                }
                else if(in_array('execution', $filters) or in_array('execution.id', $filters))
                {
                    if(!empty($executionIdList)) $testtasks = $this->dao->select('id')->from(TABLE_TESTTASK)->where('execution')->in($executionIdList)->fetchAll('id');
                }
                else
                {
                    if(!empty($projectIdList)) $testtasks = $this->dao->select('id')->from(TABLE_TESTTASK)->where('project')->in($projectIdList)->fetchAll('id');
                }

                if(!empty($testtasks))
                {
                    $moduleIdList = $this->dao->select('distinct module')->from(TABLE_CASE)->alias('t1')
                        ->leftJoin(TABLE_TESTRUN)->alias('t2')
                        ->on('t1.id = t2.case')
                        ->where('t2.task')->in(array_keys($testtasks))
                        ->fetchPairs();
                    $modules    = $this->dao->select('id, name, path, branch')->from(TABLE_MODULE)->where('id')->in($moduleIdList)->andWhere('deleted')->eq(0)->fetchAll('path');
                    $allModules = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in(join(array_keys($modules)))->andWhere('deleted')->eq(0)->fetchPairs('id', 'name');
                    $moduleTree = new stdclass();
                    foreach($modules as $module)
                    {
                        $paths = explode(',', trim($module->path, ','));
                        $this->loadModel('dataset')->genTreeOptions($moduleTree, $allModules, $paths);
                    }

                    $options = isset($moduleTree->children) ? $moduleTree->children : array();
                }

                //$defaults['casemodule']   = empty($modules) ? '' : (string)key($modules);
                $defaults['casemodule']   = '';
                $allOptions['casemodule'] = $options;
                break;
        }

        echo json_encode((Object)array('options' => $allOptions, 'defaults' => $defaults));
    }
}
