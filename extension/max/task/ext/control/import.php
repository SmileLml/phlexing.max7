<?php
helper::importControl('task');
class mytask extends task
{
    public function import($executionID)
    {
        $locate = $this->createLink('task', 'showImport', "executionID=$executionID");
        $this->session->set('showImportURL', $locate);

        echo $this->fetch('transfer', 'import', "model=task");
    }
}
