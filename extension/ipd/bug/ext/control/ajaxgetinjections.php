<?php
class myBug extends bug
{
    public function ajaxGetInjections($productID)
    {
        $programID = $this->session->project; 
        $projects  = $this->loadModel('programplan')->getPairs($programID, $productID);
        die(html::select('injection', $projects, '', "class='form-control chosen'"));
    }
}
