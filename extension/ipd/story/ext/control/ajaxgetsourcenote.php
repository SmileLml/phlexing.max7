<?php
class myStory extends story
{
    /**
     * Ajax get source note.
     *
     * @param  string     $storyType
     * @param  string     $source
     * @param  string     $from
     * @param  int|string $number
     * @access public
     * @return string
     */
    public function ajaxGetSourceNote($storyType = 'story', $source = '', $from = 'create', $number = '')
    {
        $sourceNoteWidth = '';
        if($from == 'create' and $storyType == 'requirement' and $this->config->systemMode != 'PLM') $sourceNoteWidth = "style='width: 140px;'";

        $fieldName = $fieldID = 'sourceNote';
        if($number !== '')
        {
            $fieldName = "sourceNote[$number]";
            $fieldID   = "sourceNote_$number";
        }

        if($source == 'meeting')
        {
            $meetings     = $this->loadModel('meeting')->getListByUser('all');
            $meetingPairs = array();
            foreach($meetings as $id => $meeting) $meetingPairs[$id] = $meeting->name;
            die(html::select($fieldName, arrayUnion(array('' => ''), $meetingPairs), '', "class='form-control chosen' id='$fieldID' data-max_drop_width='0' $sourceNoteWidth"));
        }
        elseif($source == 'researchreport')
        {
            $reportPairs = $this->loadModel('researchreport')->getPairs();
            die(html::select($fieldName, arrayUnion(array('' => ''), $reportPairs), '', "class='form-control chosen' id='$fieldID' data-max_drop_width='0' $sourceNoteWidth"));
        }
        else
        {
            die(html::input($fieldName, '', "class='form-control' id='$fieldID' $sourceNoteWidth"));
        }
    }
}
