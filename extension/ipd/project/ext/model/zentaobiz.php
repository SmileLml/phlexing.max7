<?php
/**
 * @param object $project
 */
public function copyWorkflowGroup($project)
{
    return $this->loadExtension('zentaobiz')->copyWorkflowGroup($project);
}
