<?php
if($this->methodName == 'export' and in_array($this->moduleName, array('story', 'epic', 'requirement')))
{
    if(in_array('excel', $lang->exportFileTypeList))
    {
        unset($lang->exportFileTypeList['excel']);
        $lang->exportFileTypeList = array('excel' => 'excel', 'word' => 'word') + $lang->exportFileTypeList;
    }
    else
    {
        $lang->exportFileTypeList = array('word' => 'word') + $lang->exportFileTypeList;
    }
}
