<?php
helper::importControl('ticket');
class myticket extends ticket
{
    /**
     * Import.
     *
     * @access public
     * @return void
     */
    public function import()
    {
        $locate = $this->createLink('ticket', 'showImport');

        $this->session->set('showImportURL', $locate);

        echo $this->fetch('transfer', 'import', "model=ticket");
    }
}
