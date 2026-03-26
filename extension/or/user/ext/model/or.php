<?php
/**
 * @param string|mixed[]|int $objectID
 * @param string|mixed[] $usersToAppended
 * @param string $type
 * @param string $params
 */
public function getTeamMemberPairs($objectID, $type = 'project', $params = '', $usersToAppended = '')
{
    if($type == 'execution')
    {
        $execution = $this->loadModel('execution')->getByID($objectID);
        if($execution->type == 'stage' and $this->app->rawModule == 'marketresearch')
        {
            $objectID = $execution->project;
            $type     = 'project';
        }
    }

    return parent::getTeamMemberPairs($objectID, $type, $params, $usersToAppended);
}
