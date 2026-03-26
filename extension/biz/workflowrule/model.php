<?php
/**
 * The model file of workflowrule module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowrule
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowruleModel extends model
{
    /**
     * Get a rule by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWRULE)->where('id')->eq($id)->fetch();
    }

    /**
     * Get a rule by type and rule.
     *
     * @param  string $type
     * @param  string $rule
     * @access public
     * @return object
     */
    public function getByTypeAndRule($type, $rule)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWRULE)->where('type')->eq($type)->andWhere('rule')->eq($rule)->limit(1)->fetch();
    }

    /**
     * Get rule list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWRULE)->orderBy($orderBy)->page($pager)->fetchAll('id', false);
    }

    /**
     * Get rule pairs.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getPairs()
    {
        return $this->dao->select('id, name')->from(TABLE_WORKFLOWRULE)->orderBy('name')->fetchPairs();
    }

    /**
     * Create a rule.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $rule = fixer::input('post')
            ->add('type', 'regex')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->remove('uid')
            ->get();

        $this->dao->insert(TABLE_WORKFLOWRULE)->data($rule)
            ->autoCheck()
            ->batchCheck($this->config->workflowrule->require->create, 'notempty')
            ->checkIF($rule->name, 'name', 'unique')
            ->exec();

        return $this->dao->lastInsertId();
    }

    /**
     * Update a rule.
     *
     * @param  int    $id
     * @access public
     * @return array
     */
    public function update($id)
    {
        $oldRule = $this->getByID($id);

        $rule = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('uid')
            ->get();

        $this->dao->update(TABLE_WORKFLOWRULE)->data($rule)
            ->where('id')->eq($id)
            ->autoCheck()
            ->batchCheck($this->config->workflowrule->require->edit, 'notempty')
            ->check('name', 'unique', "id!='$id'")
            ->exec();

        return commonModel::createChanges($oldRule, $rule);
    }

    /**
     * 检查动作是否可以点击。
     * Check if the action is clickable.
     *
     * @param  object $rule
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable($rule, $action)
    {
        $action = strtolower($action);
        if(!common::hasPriv('workflowrule', $action)) return false;

        if($action == 'edit')   return $rule->type != 'system';
        if($action == 'delete') return $rule->type != 'system';

        return true;
    }
}
