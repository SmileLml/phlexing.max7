<?php
class calendarEffort extends effortModel
{
    /**
     * Get efforts for calendar.
     *
     * @param  string $account
     * @param  string $year
     * @access public
     * @return json
     */
    public function getEfforts4Calendar($account = '', $year = '')
    {
        $lastMonth = '';
        if($year) $lastMonth = (string)($year - 1) . '-12';
        if($account == '') $account = $this->app->user->account;
        $efforts = $this->dao->select('t1.*,t2.dept')->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->beginIF($account != 'all')->where('t1.account')->eq($account)->fi()
            ->beginIF($year)->andWhere("(LEFT(`date`, 4) = '$year' OR LEFT(`date`, 7) = '$lastMonth')")->fi()
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('date, id')
            ->fetchAll('id', false);
        foreach($efforts as $id => $effort) $efforts[$id]->work = strip_tags($effort->work);

        /* Set session. */
        $this->session->set('effortReportCondition', '');
        $sql = explode('WHERE', $this->dao->get());
        if(isset($sql[1]))
        {
            $sql = explode('ORDER', $sql[1]);
            $this->session->set('effortReportCondition', $sql[0]);
        }

        $events = array();
        foreach($efforts as $id => $effort)
        {
            $event['id']       = $id;
            $event['title']    = $effort->work;
            $event['start']    = $effort->date;
            $event['end']      = $effort->date;
            $event['url']      = helper::createLink("effort", 'view', "id=$effort->id", '', true);
            $event['consumed'] = $effort->consumed;
            if($effort->objectType != 'custom') $event['title'] = '[' . strtoupper($effort->objectType[0]) . ']' . $event['title'];

            $events[] = $event;
        }
        return json_encode($events);
    }

    /**
     * Print cell.
     *
     * @param  object $col
     * @param  object $effort
     * @param  string $mode
     * @param  array  $executions
     * @access public
     * @return void
     */
    public function printCell($col, $effort, $mode = 'datatable', $executions = array())
    {
        $canView  = common::hasPriv('effort', 'view');
        $account  = $this->app->user->account;
        $id       = $col->id;
        if($col->show)
        {
            $class = '';
            $title = '';
            if($id == 'work') $title = " title='{$effort->work}'";
            if($id == 'objectType' and isset($effort->objectTitle)) $title = " title='{$effort->objectTitle}'";

            if($id == 'work' or $id == 'objectType') $class .= ' c-name';

            if($id == 'product' || $id == 'project')
            {
                static $productProjects;
                if(empty($productProjects)) $productProjects = $this->getProductProjectPairs(array(), true);
            }
            if($id == 'product')
            {
                static $products;
                if(empty($products)) $products = $this->loadModel('product')->getPairs('', 0, '', 'all');

                $effort->productName = '';
                $effortProducts      = explode(',', trim($effort->product, ','));
                foreach($effortProducts as $productID)
                {
                    if(isset($productProjects[$productID])) continue;
                    $effort->productName .= zget($products, $productID, '') . ' ';
                }
                $title = " title='{$effort->productName}'";
            }

            if($id == 'execution')
            {
                $effort->executionName = zget($executions, $effort->execution, '');
                $title = " title='{$effort->executionName}'";
            }

            if($id == 'project')
            {
                static $projects;
                if(empty($projects)) $projects = $this->loadModel('project')->getPairsByProgram();

                $effortProductID = trim($effort->product, ',');

                $effort->project     = zget($productProjects, $effortProductID , $effort->project);
                $effort->projectName = zget($projects, $effort->project, '');
                $title = " title='{$effort->projectName}'";
            }

            if($id == 'dept')
            {
                static $depts;
                if(empty($depts)) $depts = $this->loadModel('dept')->getOptionMenu();
                $effort->deptName = zget($depts, $effort->dept, '');
                $title = " title='{$effort->deptName}'";
            }

            echo "<td class='c-{$id}" . $class . "'" . $title . ">";
            switch($id)
            {
            case 'id':
                if($this->app->getModuleName() == 'my')
                {
                    echo html::checkbox('effortIDList', array($effort->id => sprintf('%03d', $effort->id)));
                }
                else
                {
                    printf('%03d', $effort->id);
                }
                break;
            case 'date':
                echo $effort->date;
                break;
            case 'account':
                static $users;
                if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');
                echo zget($users, $effort->account);
                break;
            case 'dept':
                echo $effort->deptName;
                break;
            case 'work':
                echo $canView ? html::a(helper::createLink('effort', 'view', "id=$effort->id&from=my", '', true), $effort->work, '', "class='iframe'") : $effort->work;
                break;
            case 'consumed':
                echo $effort->consumed;
                break;
            case 'left':
                echo $effort->objectType == 'task' ? $effort->left : '';
                break;
            case 'objectType':
                if($effort->objectType != 'custom')
                {
                    $viewLink    = helper::createLink($effort->objectType, 'view', "id=$effort->objectID");
                    $objectTitle = zget($this->lang->effort->objectTypeList, $effort->objectType, strtoupper($effort->objectType)) . " #{$effort->objectID} " . $effort->objectTitle;
                    $tab         = zget($this->config->effort->typeAppList, $effort->objectType, '');
                    if($effort->objectType == 'task' && $tab == 'execution')
                    {
                        $task = $this->loadModel('task')->getById($effort->objectID);
                        if($task) $execution = $this->loadModel('execution')->fetchByID($task->execution);
                        if(!empty($execution) && $execution->multiple == '0') $tab = 'project';
                    }
                    echo common::hasPriv($effort->objectType, 'view') ? html::a($viewLink, $objectTitle, '', "data-app='{$tab}'") : $objectTitle;
                }
                break;
            case 'product':
                echo $effort->productName;
                break;
            case 'execution':
                echo $effort->executionName;
                break;
            case 'project':
                echo $effort->projectName;
                break;
            case 'actions':
                common::printIcon('effort', 'edit',   "id=$effort->id", $effort, 'list', '', '', 'iframe', true);
                common::printIcon('effort', 'delete', "id=$effort->id", $effort, 'list', 'trash', 'hiddenwin');
                break;
            }
            echo '</td>';
        }
    }
}
