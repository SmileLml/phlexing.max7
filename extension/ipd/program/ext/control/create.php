<?php
class myProgram extends program
{
    /**
     * Create a program.
     *
     * @param  int    $parentProgramID
     * @param  int    $charterID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function create($parentProgramID = 0, $charterID = 0, $extra = '')
    {
        $parentProgram        = $this->program->getByID($parentProgramID);
        $this->view->charter  = $charterID ? $charterID : (isset($parentProgram->charter) ? $parentProgram->charter : 0);
        $this->view->charters = $this->loadModel('charter')->getPairs('launched', 'completionDoing,cancelDoing');

        parent::create($parentProgramID, $charterID, $extra);
    }
}
