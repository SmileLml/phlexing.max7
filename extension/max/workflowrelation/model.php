<?php
/**
 * The model file of workflowrelation module of ZDOO.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowrelation
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowrelationModel extends model
{
    /**
     * Get relations of a flow.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getList($module)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWRELATION)->where('prev')->eq($module)->orderBy('id')->fetchAll('id', false);
    }

    /**
     * Get prev relations of a flow.
     *
     * @param  string $module
     * @param  string $keyField     field | prev
     * @access public
     * @return array
     */
    public function getPrevList($module, $keyField = 'field')
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWRELATION)->where('next')->eq($module)->orderBy('id')->fetchAll($keyField, false);
    }

    /**
     * Get field of a relation.
     *
     * @param  string $prev
     * @param  string $next
     * @access public
     * @return string
     */
    public function getField($prev, $next)
    {
        return $this->dao->select('field')->from(TABLE_WORKFLOWRELATION)
            ->where('prev')->eq($prev)
            ->andWhere('next')->eq($next)
            ->limit(1)
            ->fetch('field');
    }

    /**
     * Get a relation by prev and next.
     *
     * @param  string $prev
     * @param  string $next
     * @access public
     * @return string
     */
    public function getByPrevAndNext($prev, $next)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWRELATION)
            ->where('prev')->eq($prev)
            ->andWhere('next')->eq($next)
            ->limit(1)
            ->fetch();
    }

    /**
     * Get relation layout fields of an action.
     *
     * @param  string $prev
     * @param  string $next
     * @param  string $action
     * @param  int    $ui
     * @access public
     * @return array
     */
    public function getLayoutFields($prev, $next, $action, $getRealOptions = false, $datas = array(), $ui = 0)
    {
        $this->loadModel('workflowaction', 'flow');
        $this->loadModel('workflowfield', 'flow');

        $layoutFields = $this->dao->select('t1.prev AS module, t1.field, t2.name, t2.control, t2.options, t2.type')->from(TABLE_WORKFLOWRELATIONLAYOUT)->alias('t1')
            ->leftJoin(TABLE_WORKFLOWFIELD)->alias('t2')->on('t1.prev=t2.module AND t1.field=t2.field')
            ->where('t1.prev')->eq($prev)
            ->andWhere('t1.next')->eq($next)
            ->andWhere('t1.action')->eq($action)
            ->andWhere('t1.ui')->eq($ui)
            ->orderBy('t1.order')
            ->fetchAll('field');
        $layoutFields = $this->loadModel('workflowaction', 'flow')->processFields($layoutFields, $getRealOptions, $datas);

        $fields = array();
        foreach($layoutFields as $key => $field)
        {
            if(!$field) continue;

            $fields[$key] = new stdclass();
            $fields[$key]->module  = $field->module;
            $fields[$key]->field   = $field->field;
            $fields[$key]->name    = $field->name;
            $fields[$key]->control = $field->control;
            $fields[$key]->options = $field->options;
            $fields[$key]->show    = '1';
            $fields[$key]->options = $field->options;
        }

        $fieldList = $this->workflowfield->getList($prev);
        foreach($fieldList as $field)
        {
            if(!$field) continue;
            if(isset($layoutFields[$field->field])) continue;

            $fields[$field->field] = new stdclass();
            $fields[$field->field]->module  = $field->module;
            $fields[$field->field]->field   = $field->field;
            $fields[$field->field]->name    = $field->name;
            $fields[$field->field]->control = $field->control;
            $fields[$field->field]->options = $field->options;
            $fields[$field->field]->show    = '0';
            $fields[$field->field]->options = $field->options;
        }

        return $fields;
    }

    /**
     * Get prev relation pairs of a flow.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getPrevFieldPairs($module)
    {
        return $this->dao->select('prev, field')->from(TABLE_WORKFLOWRELATION)->where('next')->eq($module)->fetchPairs();
    }

    /**
     * Get the post relations.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getRelations($module)
    {
        $relations = array();
        foreach($this->post->next as $key => $next)
        {
            $this->app->checkModuleName($next);

            $field       = $this->post->field[$key];
            $newField    = $this->post->newField[$key];
            $createField = isset($this->post->createField[$key]) ? $this->post->createField[$key] : '';

            if(!$next && !$field && !$newField) continue;

            $relation = new stdclass();
            $relation->prev        = $module;
            $relation->next        = $next;
            $relation->field       = $field;
            $relation->newField    = $newField;
            $relation->createField = $createField;
            $relation->buildin     = $this->post->buildin[$key];
            $relation->actions     = (isset($this->post->action[$key]) and $this->post->action[$key]) ? implode(',', $this->post->action[$key]) : '';
            $relation->createdBy   = $this->app->user->account;
            $relation->createdDate = helper::now();

            $relations[$key] = $relation;
        }

        return $relations;
    }

    /**
     * Check if the next flow and the field has been used in other relations.
     *
     * @param  string $module
     * @param  array  $relations
     * @access public
     * @return array
     */
    public function checkRelations($module, $relations)
    {
        $flowPairs      = $this->loadModel('workflow', 'flow')->getPairs();
        $otherRelations = $this->dao->select('prev, next, field')->from(TABLE_WORKFLOWRELATION)
            ->where('prev')->ne($module)
            ->fetchGroup('next', 'field');

        $errors = array();
        $this->loadModel('workflowfield');
        foreach($relations as $key => $relation)
        {
            if(!$relation->next) $errors['next' . $key] = sprintf($this->lang->error->notempty, $this->lang->workflowrelation->next);
            if(!$relation->createField && !$relation->field) $errors['field' . $key] = sprintf($this->lang->error->notempty, $this->lang->workflowrelation->foreignKey);
            if($relation->createField && !$relation->newField) $errors['newField' . $key] = sprintf($this->lang->error->notempty, $this->lang->workflowrelation->foreignKey);
            if($relation->createField && $relation->newField && !validater::checkREG($relation->newField, '|^[A-Za-z]+$|')) $errors['newField' . $key] = sprintf($this->lang->workflowfield->error->wrongCode, $this->lang->workflowrelation->foreignKey);

            if(!isset($otherRelations[$relation->next][$relation->field])) continue;

            $otherRelation = $otherRelations[$relation->next][$relation->field];

            $errors['field' . $key] = sprintf($this->lang->workflowrelation->error->existNextField, zget($flowPairs, $otherRelation->prev));
        }
        return $errors;
    }

    /**
     * Save relations of a flow.
     *
     * @param  string $module
     * @access public
     * @return bool | array
     */
    public function save($module)
    {
        $this->app->checkModuleName($module);

        $relations = $this->getRelations($module);

        /* Delete existed virtual actions of next modules. */
        if(empty($relations))
        {
            $this->deleteAction($module);
        }
        else
        {
            $errors = $this->checkRelations($module, $relations);
            if($errors) return array('result' => 'fail', 'message' => $errors);

            $newNexts = array();
            foreach($relations as $relation) $newNexts[] = $relation->next;

            $oldNexts = $this->dao->select('next')->from(TABLE_WORKFLOWRELATION)->where('prev')->eq($module)->andWhere('next')->notin($newNexts)->fetchPairs();
            foreach($oldNexts as $oldNext) $this->deleteAction($module, $oldNext);
        }

        $this->delete($module);

        $this->loadModel('workflowfield', 'flow');
        $flows      = $this->dao->select('id,name,`table`,`module`')->from(TABLE_WORKFLOW)->fetchAll('module');
        $fieldGroup = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->fetchGroup('module', 'field');

        $fieldIDList    = array();  // Log the created field id.
        $fieldTableList = array();  // Log the created field table.

        foreach($relations as $key => $relation)
        {
            if($relation->buildin) continue;

            $prevFlow = zget($flows, $relation->prev);
            $nextFlow = zget($flows, $relation->next);

            /* Create a field. */
            if($relation->createField)
            {
                /* Create the field of the next flow. */
                $result = $this->createField($module, $relation, $prevFlow->name, $nextFlow->table, $fieldIDList, $fieldTableList);
                if($result !== true)
                {
                    $message['field' . $key] = array_values($result);
                    return array('result' => 'fail', 'message' => $message);
                }

                $relation->field = $relation->newField;
            }

            $actionCodes = array();
            if($relation->actions)
            {
                $actionTypes = explode(',', $relation->actions);
                foreach($actionTypes as $actionType)
                {
                    if(!$actionType) continue;
                    $actionCodes[$actionType] = $this->config->workflowrelation->$actionType;
                }
            }
            $relation->actionCodes = json_encode($actionCodes);

            $this->dao->insert(TABLE_WORKFLOWRELATION)->data($relation, $skip = 'newField, createField')->exec();

            $this->createAction($module, $relation->next, $nextFlow->name, $relation->actions);

            if(dao::isError())
            {
                $errors = dao::getError();

                $this->delete($module, $fieldIDList, $fieldTableList);

                return array('result' => 'fail', 'message' => $errors);
            }

            /* Check the properties of the field. */
            if(!$relation->createField && isset($fieldGroup[$relation->next][$relation->field]))
            {
                $many2one = strpos(",{$relation->actions},", ',many2one,') !== false;
                $oldField = $fieldGroup[$relation->next][$relation->field];
                if($oldField->options != 'prevModule'
                    or ($many2one && ($oldField->type != 'text' or $oldField->control != 'multi-select'))
                    or (!$many2one && ($oldField->type != 'mediumint' or $oldField->control != 'select')))
                {
                    /* Update the field of the next flow. */
                    $result = $this->updateField($module, $relation, $nextFlow->table, $oldField, $fieldIDList, $fieldTableList);
                    if($result !== true)
                    {
                        $message['field' . $key] = $result;
                        return array('result' => 'fail', 'message' => $message);
                    }
                }
            }
        }

        return true;
    }

    /**
     * Create new action.
     *
     * @param  string $module
     * @param  string $next
     * @param  string $nextName
     * @param  string $actions
     * @access public
     * @return bool
     */
    public function createAction($module, $next, $nextName, $actions)
    {
        if(empty($actions))
        {
            $this->deleteAction($module, $next);
            return true;
        }

        $this->loadModel('workflowaction', 'flow');
        $existedActions = $this->dao->select('action')->from(TABLE_WORKFLOWACTION)
            ->where('module')->eq($module)
            ->andWhere('`virtual`')->eq(1)
            ->fetchPairs();

        $action = new stdclass();
        $action->module      = $module;
        $action->show        = 'direct';
        $action->role        = 'virtual';
        $action->virtual     = 1;
        $action->status      = 'enable';
        $action->createdBy   = $this->app->user->account;
        $action->createdDate = helper::now();

        $createActions = array('create', 'batchcreate');
        foreach($createActions as $createAction)
        {
            $relationAction = "{$next}_{$createAction}";

            if($createAction == 'create' && strpos(",{$actions},", ',many2one,') === false and strpos(",{$actions},", ',one2one,') === false)
            {
                $this->deleteAction($module, $next, $relationAction);
                continue;
            }
            if($createAction == 'batchcreate' && strpos(",{$actions},", ',one2many,') === false and strpos(",{$actions},", ',many2many,') === false)
            {
                $this->deleteAction($module, $next, $relationAction);
                continue;
            }

            if(isset($existedActions[$relationAction])) continue;

            $action->action    = $relationAction;
            $action->method    = $createAction;
            $action->name      = $this->lang->workflowaction->default->actions[$createAction] . $nextName;
            $action->type      = zget($this->config->workflowaction->default->types, $createAction, 'single');
            $action->batchMode = zget($this->config->workflowaction->default->batchModes, $createAction, 'same');
            $action->open      = zget($this->config->workflowaction->default->opens, $createAction, 'normal');
            $action->position  = (strpos(",{$actions},", ',one2many,') !== false or strpos(",{$actions},", ',one2one,') !== false) ? 'browseandview' : 'browse';

            $this->dao->insert(TABLE_WORKFLOWACTION)->data($action)->autoCheck()->exec();
        }

        return !dao::isError();
    }

    /**
     * Delete actions.
     *
     * @param  string $module
     * @param  string $next
     * @param  string $action
     * @access public
     * @return bool
     */
    public function deleteAction($module, $next = '', $action = '')
    {
        $this->dao->delete()->from(TABLE_WORKFLOWACTION)
            ->where('module')->eq($module)
            ->andWhere('role')->eq('virtual')
            ->andWhere('`virtual`')->eq(1)
            ->beginIF($action)->andWhere('action')->eq($action)->fi()
            ->beginIF(!$action && $next)->andWhere('action')->like("{$next}_%")->fi()
            ->exec();
        return !dao::isError();
    }

    /**
     * Create the field of the next flow.
     *
     * @param  string $module
     * @param  object $relation
     * @param  string $fieldName
     * @param  string $table
     * @param  array  $fieldIDList
     * @param  array  $fieldTableList
     * @access public
     * @return bool | string
     */
    public function createField($module, $relation, $fieldName, $table, &$fieldIDList, &$fieldTableList)
    {
        $many2every = strpos(",{$relation->actions},", ',many2one,') !== false || strpos(",{$relation->actions},", ',many2many,') !== false;

        $field = new stdclass();
        $field->module      = $relation->next;
        $field->field       = $relation->newField;
        $field->type        = $many2every ? 'text' : 'mediumint';
        $field->length      = $many2every ? 0 : 8;
        $field->name        = $fieldName;
        $field->control     = $many2every ? 'multi-select' : 'select';
        $field->options     = 'prevModule';
        $field->createdBy   = $this->app->user->account;
        $field->createdDate = helper::now();

        $this->dao->insert(TABLE_WORKFLOWFIELD)->data($field)->autoCheck()
            ->check('field', 'unique', "module='$field->module'")
            ->batchCheck($this->config->workflowfield->require->create, 'notempty')
            ->exec();

        if(dao::isError())
        {
            $errors = dao::getError();

            $this->delete($module, $fieldIDList, $fieldTableList);

            return $errors;
        }

        $fieldIDList[] = $this->dao->lastInsertId();    // Log the created field id.

        /* Add the new field to the table. */
        $sql = "ALTER TABLE `$table` ADD `$field->field`";
        if($many2every)  $sql .= ' text NOT NULL';
        if(!$many2every) $sql .= ' mediumint(8) unsigned NOT NULL';
        try
        {
            $this->dbh->query($sql);

            $fieldTableList[$table] = $field->field;  // Log the created field table.
        }
        catch(PDOException $exception)
        {
            $this->delete($module, $fieldIDList, $fieldTableList);

            return $exception->getMessage();
        }

        return true;
    }

    /**
     * Update the field of the next flow.
     *
     * @param  string $module
     * @param  obejct $relation
     * @param  string $table
     * @param  object $oldField
     * @param  array  $fieldIDList
     * @param  array  $fieldTableList
     * @access public
     * @return bool | string
     */
    public function updateField($module, $relation, $table, $oldField, $fieldIDList, $fieldTableList)
    {
        $many2every = strpos(",{$relation->actions},", ',many2one,') !== false || strpos(",{$relation->actions},", ',many2many,') !== false;

        $field = new stdclass();
        $field->type    = $many2every ? 'text' : 'mediumint';
        $field->length  = $many2every ? 0 : 8;
        $field->control = $many2every ? 'multi-select' : 'select';
        $field->options = 'prevModule';

        $this->dao->update(TABLE_WORKFLOWFIELD)->data($field)
            ->where('module')->eq($relation->next)
            ->andWhere('field')->eq($relation->field)
            ->exec();

        if(dao::isError())
        {
            $errors = dao::getError();

            $this->delete($module, $fieldIDList, $fieldTableList);

            return $errors;
        }

        if($many2every && ($oldField->type != 'text' or $oldField->control != 'multi-select')
            or (!$many2every && ($oldField->type != 'mediumint' or $oldField->control != 'select')))
        {
            /* Process the table of the next flow. */
            $field  = $this->workflowfield->getByField($relation->next, $relation->field);
            $result = $this->workflowfield->processTable($table, $oldField, $field);

            /* If failed to process the table, log error and delete the saved relations, then return false. */
            if(isset($result['result']) && $result['result'] == 'fail')
            {
                $this->delete($module, $fieldIDList, $fieldTableList);

                return $result['message'];
            }
        }

        return true;
    }

    /**
     * Update the field of a relation.
     *
     * @param  string $next
     * @param  string $oldField
     * @param  string $newField
     * @access public
     * @return bool
     */
    public function updateRelation($next, $oldField, $newField)
    {
        $this->dao->update(TABLE_WORKFLOWRELATION)->set('field')->eq($newField)
            ->where('next')->eq($next)
            ->andWhere('field')->eq($oldField)
            ->exec();

        return !dao::isError();
    }

    /**
     * Delete a relation.
     *
     * @param  string $next
     * @param  string $field
     * @access public
     * @return bool
     */
    public function deleteRelation($next, $field)
    {
        $this->dao->delete()->from(TABLE_WORKFLOWRELATION)
            ->where('next')->eq($next)
            ->andWhere('field')->eq($field)
            ->exec();

        return !dao::isError();
    }

    /**
     * Delete relations of a flow.
     *
     * @param  string $module
     * @param  object $null
     * @access public
     * @return bool
     */
    public function delete($module, $fieldIDList = array(), $fieldTableList = array())
    {
        $this->dao->delete()->from(TABLE_WORKFLOWRELATION)->where('prev')->eq($module)->andWhere('buildin')->ne('1')->exec();

        if($fieldIDList)
        {
            $this->dao->delete()->from(TABLE_WORKFLOWFIELD)->where('id')->in($fieldIDList)->exec();
        }

        if($fieldTableList)
        {
            try
            {
                foreach($fieldTableList as $table => $field)
                {
                    $this->dbh->query("ALTER TABLE `$table` DROP `$field`");
                }
            }
            catch(PDOException $exception)
            {
                return $exception->getMessage();
            }
        }

        return !dao::isError();
    }
}
