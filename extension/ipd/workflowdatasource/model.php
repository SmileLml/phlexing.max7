<?php
/**
 * The model file of workflowdatasource module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowdatasourceModel extends model
{
    /**
     * Get apps of datasource.
     *
     * @access public
     * @return array
     */
    public function getApps()
    {
        $apps = $this->loadModel('workflow', 'flow')->getApps(false);

        return arrayUnion($apps, $this->lang->workflowdatasource->apps);
    }

    /**
     * Get modules of an app.
     *
     * @access public
     * @return array
     */
    public function getModules()
    {
        $modules = array();
        if(isset($this->config->workflowdatasource->methods) && is_array($this->config->workflowdatasource->methods))
        {
            foreach($this->config->workflowdatasource->methods as $module => $methods)
            {
                $this->app->loadLang($module);
                $modules[$module] = isset($this->lang->$module->common) ? $this->lang->$module->common : $module;
            }
        }

        if($this->config->vision == 'lite') unset($modules['branch'], $modules['bug'], $modules['build'], $modules['productplan'], $modules['testcase'], $modules['caselib'], $modules['testtask']);

        return $modules;
    }

    /**
     * Get methods of a module.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getModuleMethods($module)
    {
        if(empty($this->config->workflowdatasource->methods[$module]) || !is_array($this->config->workflowdatasource->methods[$module])) return array();

        $methods = array();
        foreach($this->config->workflowdatasource->methods[$module] as $method) $methods[] = array('text' => $method, 'value' => $method);
        return $methods;
    }

    /**
     * Get comments of a method.
     *
     * @param  string $module
     * @param  string $method
     * @param  int    $methodDescOnly
     * @access public
     * @return array
     */
    public function getMethodComments($module, $method, $methodDescOnly = false)
    {
        $model = $this->loadModel($module);
        $methodReflect = new ReflectionMethod($model, $method);
        $comment = $methodReflect->getDocComment();

        /* Strip the opening and closing tags of the docblock. */
        $comment = substr($comment, 3, -2);

        /* Split into arrays of lines. */
        $comment = preg_split('/\r?\n\r?/', $comment);

        /* Group the lines together by @tags */
        $blocks = array();
        $b = -1;
        foreach ($comment as $line)
        {
            /* Trim asterisks and whitespace from the beginning and whitespace from the end of lines. */
            $line = ltrim(rtrim($line), "* \t\n\r\0\x0B");
            if (isset($line[1]) && $line[0] == '@' && ctype_alpha($line[1]))
            {
                $b++;
                $blocks[] = array();
            } else if($b == -1) {
                $b = 0;
                $blocks[] = array();
            }
            $blocks[$b][] = $line;
        }

        $result = array();
        /* Parse the blocks */
        foreach ($blocks as $block => $body)
        {
            $body = trim(implode("\n", $body));
            if($block == 0 && !(isset($body[1]) && $body[0] == '@' && ctype_alpha($body[1])))
            {
                /* This is the description block */
                if($methodDescOnly) return $body;

                $result['desc'] = $body;
                continue;
            }
            else
            {
                /* This block is tagged */
                if(preg_match('/^@[a-z0-9_]+/', $body, $matches))
                {
                    $tag  = substr($matches[0], 1);
                    $body = substr($body, strlen($tag)+2);
                    if($tag == 'param')
                    {
                        $parts          = preg_split('/\s+/', trim($body), 3);
                        $parts          = array_pad($parts, 3, null);
                        $property       = array('type', 'var', 'desc');
                        $param          = array_combine($property, $parts);
                        $param['var']   = substr($param['var'], 1);
                        $result['param'][$param['var']] = $param;
                    }
                    else
                    {
                        $result[$tag][] = $body;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Get default params of a method.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return array
     */
    public function getDefaultParams($module, $method)
    {
        $params = array();

        $defaultValueFiles = glob($this->app->getTmpRoot() . "defaultvalue/*.php");
        if($defaultValueFiles) foreach($defaultValueFiles as $file) include $file;

        $model = $this->loadModel($module);
        $methodReflect = new reflectionMethod($model, $method);

        foreach($methodReflect->getParameters() as $param)
        {
            $name    = $param->getName();
            $default = '';
            if(isset($paramDefaultValue[$module][$method][$name]))
            {
                $default = $paramDefaultValue[$module][$method][$name];
            }
            elseif($param->isDefaultValueAvailable())
            {
                $default = $param->getDefaultValue();
            }
            $params[$name] = $default;
        }
        return $params;
    }

    /**
     * Get field pairs of a view.
     *
     * @param  string $sql
     * @access public
     * @return array
     */
    public function getViewFields($sql)
    {
        if(empty($sql)) return array('');

        $view = 'view_datasource_tmp';
        $sql  = "CREATE OR REPLACE VIEW $view AS $sql";
        $this->dbh->query($sql);

        $sqlFields = $this->dbh->query("DESC $view")->fetchAll();

        $i = 1;
        $fields = array();
        foreach($sqlFields as $field)
        {
            $fields["field{$i}"] = $field->Field;
            $i++;
        }

        $sql = "DROP VIEW $view";
        $this->dbh->query($sql);

        return $fields;
    }

    /**
     * Get a datasource by id.
     *
     * @param  int    $id
     * @access public
     * @return objcet
     */
    public function getByID($id)
    {
        $datasource = $this->dao->select('*')->from(TABLE_WORKFLOWDATASOURCE)->where('id')->eq($id)->fetch();
        if($datasource)
        {
            $datasource->options = array();
            $datasource->app        = '';
            $datasource->module     = '';
            $datasource->method     = '';
            $datasource->methodDesc = '';
            $datasource->params     = array();
            $datasource->sql        = '';
            $datasource->lang       = '';

            if($datasource->type == 'option')
            {
                $datasource->options = json_decode($datasource->datasource, true);
            }
            elseif($datasource->type == 'system')
            {
                $data = json_decode($datasource->datasource);
                $datasource->app        = $data->app;
                $datasource->module     = $data->module;
                $datasource->method     = $data->method;
                $datasource->methodDesc = $data->methodDesc;
                $datasource->params     = $data->params;
            }
            elseif($datasource->type == 'sql')
            {
                $datasource->sql = $datasource->datasource;
            }
            elseif($datasource->type == 'lang')
            {
                $datasource->lang = $datasource->datasource;
            }
            elseif($datasource->type == 'func')
            {
            }
            elseif($datasource->type == 'category')
            {
            }
        }
        return $datasource;
    }

    /**
     * Get datasource list.
     *
     * @param  string $orderBy
     * @param  objcet $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWDATASOURCE)
            ->where(1)
            ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
            ->beginIF($this->config->systemMode == 'light')->andWhere('code')->notin($this->config->workflowdatasource->notShowInLightMode)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Get datasource pairs.
     *
     * @param  string $params
     * @access public
     * @return array
     */
    public function getPairs($params = '')
    {
        $datasources = $this->dao->select('id, name')->from(TABLE_WORKFLOWDATASOURCE)
            ->where(1)
            ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
            ->fetchPairs();

        foreach($this->lang->workflowdatasource->options as $key => $name) $datasources[$key] = $name;

        if(strpos($params, 'noempty') === false) $datasources = arrayUnion(array('' => ''), $datasources);

        return $datasources;
    }

    /**
     * Get datasource id pairs which type is category.
     *
     * @access public
     * @return array
     */
    public function getCategoryDatasources()
    {
        return $this->dao->select('id')->from(TABLE_WORKFLOWDATASOURCE)->where('type')->eq('category')->fetchPairs();
    }

    /**
     * Create a datasource.
     *
     * @access public
     * @return bool | int
     */
    public function create()
    {
        $datasource = fixer::input('post')
            ->setDefault('datasource', '')
            ->add('vision', $this->config->vision)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->skipSpecial('sql')
            ->get();

        $datasource->keyField   = html_entity_decode($datasource->keyField, ENT_QUOTES);
        $datasource->valueField = html_entity_decode($datasource->valueField, ENT_QUOTES);

        $result = $this->fix($datasource);
        if(!empty($result))
        {
            dao::$errors = $result;
            return false;
        }

        $this->dao->insert(TABLE_WORKFLOWDATASOURCE)->data($datasource, $skip = 'options, app, module, method, methodDesc, paramName, paramType, paramDesc, paramValue, sql, lang')
            ->autoCheck()
            ->checkIF($datasource->type != 'category' && $datasource->type != 'func', 'datasource', 'notempty')
            ->checkIF($datasource->code, 'code', 'unique')
            ->exec();

        if(dao::isError()) return false;

        $datasourceID = $this->dao->lastInsertId();

        if($datasource->type == 'sql')
        {
            try
            {
                $view   = "view_datasource_$datasourceID";
                $fields = $this->getViewFields($datasource->sql);
                $fields = implode(',', array_keys($fields));
                $sql    = "CREATE VIEW $view ($fields) AS $datasource->sql";

                $this->dbh->query($sql);

                $this->dao->update(TABLE_WORKFLOWDATASOURCE)->set('view')->eq($view)->where('id')->eq($datasourceID)->exec();
            }
            catch(PDOException $exception)
            {
                $this->delete($datasourceID);

                dao::$errors = $exception->getMessage();
            }
        }

        return $datasourceID;
    }

    /**
     * Update a datasource.
     *
     * @param  int    $id
     * @access public
     * @return bool | array
     */
    public function update($id)
    {
        $oldDatasource = $this->getByID($id);
        if($oldDatasource->buildin) return false;

        $datasource = fixer::input('post')
            ->setDefault('datasource', '')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->skipSpecial('sql')
            ->get();

        $datasource->keyField   = html_entity_decode($datasource->keyField, ENT_QUOTES);
        $datasource->valueField = html_entity_decode($datasource->valueField, ENT_QUOTES);

        $result = $this->fix($datasource);
        if(!empty($result))
        {
            dao::$errors = $result;
            return false;
        }

        if($datasource->type == 'sql' && $datasource->datasource != $oldDatasource->datasource)
        {
            try
            {
                $sqls   = array();
                $fields = $this->getViewFields($datasource->datasource);
                $fields = implode(',', array_keys($fields));
                $view   = "view_datasource_$id";

                $sqls[] = "DROP VIEW IF EXISTS $view";
                $sqls[] = "CREATE VIEW $view ($fields) AS $datasource->datasource";

                foreach($sqls as $sql) $this->dbh->query($sql);

                $datasource->view = $view;
            }
            catch(PDOException $exception)
            {
                dao::$errors = $exception->getMessage();
                return false;
            }
        }

        $this->dao->update(TABLE_WORKFLOWDATASOURCE)->data($datasource, $skip = 'options, app, module, method, methodDesc, paramName, paramType, paramDesc, paramValue, sql, lang')
            ->where('id')->eq($id)
            ->autoCheck()
            ->checkIF($datasource->type != 'category' && $datasource->type != 'func', 'datasource', 'notempty')
            ->checkIF($datasource->code, 'code', 'unique', "id!={$id}")
            ->exec();

        unset($oldDatasource->options);
        unset($datasource->options);

        return commonModel::createChanges($oldDatasource, $datasource);
    }

    /**
     * Fix a datasource.
     *
     * @param  object $datasource
     * @access public
     * @return array
     */
    public function fix($datasource)
    {
        $errors = array();

        if(!$datasource->name) $errors['name'] = sprintf($this->lang->error->notempty, $this->lang->workflowdatasource->name);
        if($datasource->type == 'option')
        {
            $options = array();
            foreach($datasource->options['value'] as $key => $value)
            {
                if(empty($value)) continue;
                if(empty($datasource->options['text'][$key])) continue;

                $options[$value] = $datasource->options['text'][$key];
            }

            if(empty($options)) $errors['options'] = $this->lang->workflowdatasource->error->emptyOptions;

            $datasource->datasource = helper::jsonEncode($options);
        }
        elseif($datasource->type == 'system')
        {
            if(!$datasource->method) $errors['method'] = sprintf($this->lang->error->notempty, $this->lang->workflowdatasource->method);
            if(!$datasource->module) $errors['module'] = sprintf($this->lang->error->notempty, $this->lang->workflowdatasource->module);

            $params = array();
            if($this->post->paramName)
            {
                foreach($this->post->paramName as $key => $name)
                {
                    $param = new stdclass();
                    $param->name  = $name;
                    $param->type  = $this->post->paramType[$key];
                    $param->desc  = $this->post->paramDesc[$key];
                    $param->value = $this->post->paramValue[$key];

                    $params[$key] = $param;
                }
            }

            $data = new stdclass();
            $data->app        = $datasource->app;
            $data->module     = $datasource->module;
            $data->method     = $datasource->method;
            $data->methodDesc = $datasource->methodDesc;
            $data->params     = $params;

            $datasource->datasource = helper::jsonEncode($data);
        }
        elseif($datasource->type == 'sql')
        {
            if(empty($datasource->sql))        $errors['sql']        = sprintf($this->lang->error->notempty, $this->lang->workflowdatasource->sql);
            if(empty($datasource->keyField))   $errors['keyField']   = sprintf($this->lang->error->notempty, $this->lang->workflowdatasource->key);
            if(empty($datasource->valueField)) $errors['valueField'] = sprintf($this->lang->error->notempty, $this->lang->workflowdatasource->value);

            $datasource->datasource = $datasource->sql;
        }
        elseif($datasource->type == 'lang')
        {
            $datasource->datasource = $datasource->lang;
        }
        elseif($datasource->type == 'func')
        {
        }

        return $errors;
    }

    /**
     * Delete a datasource.
     *
     * @param  int    $id
     * @param  int    $null
     * @access public
     * @return bool
     */
    public function delete($id, $null = null)
    {
        try
        {
            $view = "view_datasource_$id";
            $sql  = "DROP VIEW IF EXISTS $view";
            $this->dbh->query($sql);
            $this->dao->delete()->from(TABLE_WORKFLOWDATASOURCE)->where('id')->eq($id)->exec();
        }
        catch(PDOException $exception)
        {
            dao::$errors = $exception->getMessage();
        }
        return !dao::isError();
    }

    /**
     * 检查动作是否可以点击。
     * Check if the action is clickable.
     *
     * @param  object $datasource
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable($datasource, $action)
    {
        $action = strtolower($action);
        if(!common::hasPriv('workflowdatasource', $action)) return false;

        if($action == 'edit')   return $datasource->buildin != 1;
        if($action == 'delete') return $datasource->buildin != 1;
        if($action == 'manage') return $datasource->buildin != 1 && $datasource->type == 'category' && common::hasPriv('tree', 'browse');

        return true;
    }
}
