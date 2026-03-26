<?php
public function printWebTree($parentID, $programGroup, $module, $method)
{
    if(!isset($programGroup[$parentID])) return false;

    echo "<div class='list'>";
    foreach($programGroup[$parentID] as $id => $program)
    {
        echo html::a(helper::createLink($module, $method, "programID={$program->id}"), $program->name, '', "class='item' data-id='{$program->id}' data-tag=':{$program->status}'");
        $this->printWebTree($id, $programGroup, $module, $method);
    }
    echo "</div>";
}
