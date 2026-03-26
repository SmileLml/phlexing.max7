<?php
/**
 * The model file of excel module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     excel
 * @link        https://www.zentao.net
 */
class excelTask extends taskModel
{
    /**
     * Create from import.
     *
     * @param  array  $tasks
     * @access public
     * @return array
     */
    public function createFromImport($tasks)
    {
        $this->loadModel('action');
        $this->loadModel('file');
        $this->loadModel('story');
        $now  = helper::now();
        $data = fixer::input('post')->get();
        if(empty($data->team)) $data->team = array();

        if(!$this->post->insert && !empty($_POST['id']))
        {
            $oldTasks = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in(($_POST['id']))->fetchAll('id', false);
            $oldTeams = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('`task`')->in(($_POST['id']))->fetchGroup('task', 'id');
        }

        $tasksID   = array();
        $levelList = array();
        foreach($tasks as $key => $taskData)
        {
            $taskID = 0;
            if(!empty($_POST['id'][$key]) and empty($_POST['insert']))
            {
                $taskID = $data->id[$key];
                if(!isset($oldTasks[$taskID])) $taskID = 0;
            }

            if($data->mode[$key])
            {
                if($data->assignedTo[$key] && isset($data->team[$key]) && !in_array($data->assignedTo[$key], $data->team[$key]))
                {
                    dao::$errors["assignedTo[{$key}]"] = $this->lang->task->error->assignedToError;
                    return false;
                }
                if(empty($data->mode[$key])) $taskData->mode = 'linear';
            }

            if($taskID)
            {
                /* Process assignedTo.*/
                if($taskData->story != $oldTasks[$taskID]->story) $taskData->storyVersion = $this->story->getVersion($taskData->story);
                $taskData->desc   = str_replace('src="' . common::getSysURL() . '/', 'src="', $taskData->desc);
                $taskData->status = $oldTasks[$taskID]->status;

                $oldTask = (array)$oldTasks[$taskID];
                $newTask = (array)$taskData;
                $oldTask['desc'] = trim($this->file->excludeHtml($oldTask['desc'], 'noImg'));
                $newTask['desc'] = trim($this->file->excludeHtml($newTask['desc'], 'noImg'));
                $changes = common::createChanges((object)$oldTask, (object)$newTask);
                if(empty($changes)) continue;

                /* Ignore updating tasks for different executions. */
                if($oldTask['execution'] != $newTask['execution']) continue;

                if($oldTask['estimate'] == 0 and $oldTask['left'] == 0) $taskData->left = $taskData->estimate;

                $taskData->lastEditedBy   = $this->app->user->account;
                $taskData->lastEditedDate = $now;

                $this->dao->update(TABLE_TASK)->data($taskData)
                    ->checkFlow()
                    ->where('id')->eq((int)$taskID)->exec();

                if(!dao::isError())
                {
                    if($oldTask['parent'] > 0)$this->updateParentStatus($oldTask['id']);
                    $actionID = $this->action->create('task', $taskID, 'Edited', '');
                    $this->action->logHistory($actionID, $changes);
                    $tasksID[$key] = $taskID;
                }
            }
            else
            {
                if($taskData->story != false) $taskData->storyVersion = $this->story->getVersion($taskData->story);
                $taskData->left       = $taskData->estimate;
                $taskData->status     = 'wait';
                $taskData->openedBy   = $this->app->user->account;
                $taskData->openedDate = $now;
                $taskData->vision     = $this->config->vision;

                if($taskData->deadline != '' and $taskData->estStarted != '' and strtotime($taskData->deadline) < strtotime($taskData->estStarted)) continue;
                $this->dao->insert(TABLE_TASK)->data($taskData)
                    ->autoCheck()
                    ->checkFlow()
                    ->exec();

                if(!dao::isError())
                {
                    $taskID = $this->dao->lastInsertID();
                    $this->dao->update(TABLE_TASK)->set('path')->eq(",$taskID,")->where('id')->eq($taskID)->exec();

                    $taskData->id = $taskID;
                    if(isset($data->level[$key]) && empty($data->mode[$key]))
                    {
                        $level = $data->level[$key];
                        if($level && preg_match('/^\d+(\.\d+)*$/', $level))
                        {
                            $levelList[$level] = $taskID;
                            $parentLevel = substr($level, 0, strrpos($level, '.'));
                            if($this->config->vision != 'lite' && isset($levelList[$parentLevel]))
                            {
                                $taskData->parent = $levelList[$parentLevel];
                                $this->dao->update(TABLE_TASK)->set('parent')->eq($taskData->parent)->where('id')->eq($taskID)->exec();

                                $this->updateParent($taskData, false);
                            }
                        }
                    }

                    $this->loadModel('action')->create('task', $taskID, 'Opened', '');

                    if(!empty($taskData->story))
                    {
                        $relation = new stdClass();
                        $relation->relation = 'generated';
                        $relation->AID      = $taskData->story;
                        $relation->AType    = 'story';
                        $relation->BID      = $taskID;
                        $relation->BType    = 'task';
                        $relation->product  = 0;
                        $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
                    }

                    $tasksID[$key] = $taskID;
                }
            }

            $teams = array();
            if($data->mode[$key])
            {
                $oldTeam = isset($oldTeams[$key]) ? $oldTeams[$key] : array();
                if(!isset($data->team[$key])) $data->team[$key] = array();
                foreach($data->team[$key] as $id => $account)
                {
                    if(!$account or isset($teams[$account])) continue;

                    $member = new stdclass();
                    foreach($oldTeam as $teamID => $oldMember)
                    {
                        if($oldMember->account == $account)
                        {
                            $member = $oldMember;
                            unset($oldTeam[$teamID]);
                            break;
                        }
                    }

                    $member->task     = $taskID;
                    $member->account  = $account;
                    $member->estimate = $data->estimate[$key][$id];
                    $member->status   = 'wait';
                    if(!isset($member->left))  $member->left  = $member->estimate;
                    if(!isset($member->order)) $member->order = $id;
                    unset($member->id);

                    $teams[] = $member;
                }

                $this->dao->delete()->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->exec();
                if(count($teams) >= 2)
                {
                    foreach($teams as $team) $this->dao->insert(TABLE_TASKTEAM)->data($team)->autoCheck()->exec();
                    $task = $this->getByID($taskID);
                    $this->computeMultipleHours($task);
                }
                else
                {
                    $this->dao->update(TABLE_TASK)->set('mode')->eq('')->where('id')->eq($taskID)->exec();
                }
            }
        }

        if($this->post->isEndPage)
        {
            if(isset($_SESSION['fileImportFileName'])) unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
        }

        return $tasksID;
    }

    /**
     * Process datas for task (split multiplayer tasks) and child tasks.
     *
     * @param  int    $taskData
     * @access public
     * @return void
     */
    public function processDatas4Task($taskData)
    {
        $estimateList = array();
        $parentList   = array();
        $datas = $taskData->datas;
        foreach($datas as $key => $data)
        {
            if(!$data) continue;
            foreach($data as $field => $value)
            {
                if(!$value) continue;
                if(($field == 'estimate' or $field == 'left' or $field == 'consumed') and strrpos($value, ':') !== false)
                {
                    $valueArray = explode("\n", $value);
                    $tmpArray = array();
                    foreach($valueArray as $tmpValue)
                    {
                        if(!$tmpValue) continue;
                        if(strpos($tmpValue, ':') === false) continue;
                        $tmpValue = explode(':', $tmpValue);
                        $account   = trim($tmpValue[0]);
                        if(strpos($account, '(#') !== false)
                        {
                            $account = trim(substr($account, strrpos($account, '(#') + 2), ')');
                        }
                        elseif(strpos($account, '#') === 0)
                        {
                            $account = trim(substr($tmpValue[0], 1));
                        }

                        $estimate  = $tmpValue[1];
                        $tmpArray[$account] = $estimate;
                    }
                    $data->$field = $tmpArray;
                }
                elseif($field == 'name')
                {
                    if(strpos($value, '[' . $this->lang->task->multipleAB . '] ') === 0) $value = str_replace('[' . $this->lang->task->multipleAB . '] ', '', $value);
                    $data->$field = $value;
                }
            }

            $data->execution = $this->session->taskTransferParams['executionID'];
            $datas[$key] = $data;
        }

        if($parentList) foreach($parentList as $line) if(isset($datas[$line])) $datas[$line]->estimate = $estimateList[$line];
        $taskData->datas = $datas;
        return $taskData;
    }
}
