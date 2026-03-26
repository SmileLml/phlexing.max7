<?php
helper::importControl('bug');
class mybug extends bug
{
    public function import($productID, $branch = 0)
    {
        $locate = $this->createLink('bug', 'showImport', "productID=$productID&branch=$branch");
        $this->session->set('showImportURL', $locate);

        echo $this->fetch('transfer', 'import', "model=bug");
    }
}
