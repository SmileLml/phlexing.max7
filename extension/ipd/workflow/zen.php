<?php
class workflowZen extends workflow
{
    protected function buildCanQuoteTree($module, $groupID)
    {
        $currentTables = $this->dao->select('module')->from(TABLE_WORKFLOW)->where('type')->eq('table')->andWhere('`group`')->eq($groupID)->fetchPairs();
        $quoteTables   = $this->dao->select('*')->from(TABLE_WORKFLOW)
            ->where('type')->eq('table')
            ->andWhere('`group`')->ne($groupID)
            ->andWhere('`role`')->eq('custom')
            ->andWhere('module')->notin(array_keys($currentTables))
            ->andWhere('parent')->eq($module)
            ->fetchGroup('group', 'id');

        $groups = $this->loadModel('workflowgroup')->getByIdList(array_keys($quoteTables));

        $tree = array();
        foreach($quoteTables as $group => $tables)
        {
            if($group && !isset($groups[$group])) continue;

            $groupName = empty($groups[$group]) ? $this->lang->workflowgroup->workflow->exclusiveList[0] : $groups[$group]->name;
            $subItems  = array();
            foreach($tables as $table)
            {
                $subItem = new stdclass();
                $subItem->id     = $table->id;
                $subItem->text   = $table->name;
                $subItem->module = $table->module;
                $subItems[]      = $subItem;
            }
            if(empty($subItems)) continue;

            $item = new stdclass();
            $item->id    = "group_{$group}";
            $item->text  = $groupName;
            $item->items = $subItems;
            $tree[] = $item;
        }

        return $tree;
    }
}
