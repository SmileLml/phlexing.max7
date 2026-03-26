<?php
helper::importControl('testcase');
class mytestcase extends testcase
{
    public function import($productID, $branch = 0)
    {
        $locate = inlink('showImport', "productID=$productID&branch=$branch");
        $this->session->set('showImportURL', $locate);
        echo $this->fetch('transfer', 'import', "model=testcase");
    }
}
