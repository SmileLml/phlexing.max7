<?php
/**
 * The zen file of demandpool module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu<hufangzhou@easycorp.ltd>
 * @package     demandpool
 * @link        https://www.zentao.net
 */

class demandpoolZen extends demandpool
{
    /**
     * 为跟踪矩阵设置自定义列。
     * Get custom fields for track.
     *
     * @param  string    $storyType   demand|epic|requirement|story
     * @access protected
     * @return array
     */
    public function getCustomFieldsForTrack($storyType)
    {
        $listFields = array();
        $listFields['demand']      = $this->lang->demand->common;
        $listFields['epic']        = $this->lang->ERCommon;
        $listFields['requirement'] = $this->lang->URCommon;
        $listFields['story']       = $this->lang->SRCommon;
        $listFields['project']     = $this->lang->story->project;
        $listFields['execution']   = $this->lang->story->execution;
        $listFields['design']      = $this->lang->story->design;
        $listFields['commit']      = $this->lang->story->repoCommit;
        $listFields['task']        = $this->lang->story->tasks;
        $listFields['bug']         = $this->lang->story->bugs;
        $listFields['case']        = $this->lang->story->cases;

        if($storyType == 'requirement' || $storyType == 'story') unset($listFields['requirement']);
        if($storyType == 'story') unset($listFields['story']);

        return array('list' => $listFields, 'show' => array_keys($listFields));
    }
}
