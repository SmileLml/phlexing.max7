<?php
helper::importControl('caselib');
class mycaselib extends caselib
{
    public function import($libID)
    {
        $locate = inlink('showImport', "libID=$libID");

        $this->session->set('showImportURL', $locate);

        echo $this->fetch('transfer', 'import', "model=testcase");
    }
}
