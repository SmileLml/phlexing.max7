<?php
class workflowfieldZen extends workflowfield
{
    protected function buildCanQuoteTree($module, $groupID)
    {
        $customedFields = $this->workflowfield->getCustomedFields($module, $groupID);
        $quoteFields    = $this->dao->select('field')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('group')->eq($groupID)->andWhere('role')->eq('quote')->fetchAll('field');
        $groups         = $this->loadModel('workflowgroup')->getByIdList(array_keys($customedFields));

        $tree = array();
        foreach($customedFields as $group => $fields)
        {
            if($group && !isset($groups[$group])) continue;

            $groupName = empty($groups[$group]) ? $this->lang->workflowgroup->workflow->exclusiveList[0] : $groups[$group]->name;
            $subItems  = array();
            foreach($fields as $field)
            {
                if(isset($quoteFields[$field->field])) continue;
                $subItem = new stdclass();
                $subItem->id    = $field->id;
                $subItem->field = $field->field;
                $subItem->text  = $field->name;
                $subItems[]     = $subItem;
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
