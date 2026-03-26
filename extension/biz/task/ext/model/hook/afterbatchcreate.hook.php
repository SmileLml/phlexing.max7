<?php
if($parent && $taskIdList)
{
    $this->loadExtension('gantt')->unlinkRelation((int)$parent->id);
}
