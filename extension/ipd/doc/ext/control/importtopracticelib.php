<?php
class myDoc extends doc
{
    /**
     * Import doc to practice lib.
     *
     * @param  int    $docID
     * @access public
     * @return void
     */
    public function importToPracticeLib($docID)
    {
        $this->doc->importToLib(array($docID), 'practice');
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'load' => true));
    }
}
