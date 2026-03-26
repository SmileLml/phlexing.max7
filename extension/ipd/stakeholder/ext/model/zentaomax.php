<?php
/**
 * Save stakeholder plan.
 *
 * @param  string $browseType
 * @param  string $orderBy
 * @param  object $pager
 * @access public
 * @return array
 */
public function savePlan()
{
    $data = fixer::input('post')->get();

    foreach($data->status as $activityID => $value)
    {
        $stakeholder = new stdclass();
        $stakeholder->activity    = $activityID;
        $stakeholder->status      = $data->status[$activityID];
        $stakeholder->begin       = $data->begin[$activityID];
        $stakeholder->realBegin   = $data->realBegin[$activityID];
        $stakeholder->partake     = json_encode($data->partake[$activityID]);
        $stakeholder->situation   = $data->situation[$activityID];
        $stakeholder->project     = $this->session->project;
        $stakeholder->createdBy   = $this->app->user->account;
        $stakeholder->createdDate = helper::today();

        $this->dao->replace(TABLE_INTERVENTION)->data($stakeholder)->exec();
    }

    return !dao::isError();
}
