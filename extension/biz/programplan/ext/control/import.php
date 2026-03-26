<?php
helper::importControl('programplan');
class myprogramplan extends programplan
{
    public function import($projectID)
    {
        $locate = $this->createLink('programplan', 'showImport', "projectID=$projectID");
        $this->session->set('showImportURL', $locate);

        echo $this->fetch('transfer', 'import', "model=task");
    }
}
