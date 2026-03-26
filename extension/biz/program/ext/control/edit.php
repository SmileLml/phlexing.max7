<?php
class myProgram extends program
{
    /**
     * Edit a program.
     *
     * @param  int    $programID
     * @param  int    $parentProgramID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function edit($programID = 0, $parentProgramID = 0, $extra = '')
    {
        $program       = $this->program->getByID($programID);
        $parentProgram = $this->program->getByID($parentProgramID);

        $charters       = $this->loadModel('charter')->getPairs('launched', 'completionDoing,cancelDoing');
        $programCharter = $this->charter->fetchByID($program->charter);
        if(isset($programCharter->id)) $charters[$programCharter->id] = $programCharter->name;

        $processedExtra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($processedExtra, $output);

        $this->view->charters = $charters;
        $this->view->charter  = !empty($output['charter']) ? $output['charter'] : (isset($parentProgram->charter) ? $parentProgram->charter : $program->charter);

        parent::edit($programID, $parentProgramID);
    }
}
