<?php
if(!empty($case->confirmeObject))
{
    $mothed = $case->confirmeObject['type'] == 'confirmedretract' ? 'confirmDemandRetract' : 'confirmDemandUnlink';
    return $this->buildMenu('testcase', $mothed, "objectID=$case->id&object=case&extra={$case->confirmeObject['id']}", $case, 'view', 'search', '', 'iframe', true);
}
