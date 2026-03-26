<?php
helper::importControl('user');
class myuser extends user
{
    public function import()
    {
        $locate = $this->createLink('user', 'showImport');
        $this->session->set('showImportURL', $locate);

        echo $this->fetch('transfer', 'import', "model=user");
    }
}
