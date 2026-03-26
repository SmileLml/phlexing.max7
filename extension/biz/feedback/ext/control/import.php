<?php
helper::importControl('feedback');
class myfeedback extends feedback
{
    /**
     * Import.
     *
     * @access public
     * @return void
     */
    public function import()
    {
        $locate = $this->createLink('feedback', 'showImport');

        $this->session->set('showImportURL', $locate);

        echo $this->fetch('transfer', 'import', "model=feedback");
    }
}
