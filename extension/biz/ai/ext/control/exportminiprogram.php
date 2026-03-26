<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * Export a mini program as zip file.
     *
     * @param string $appID
     * @access public
     * @return void
     */
    public function exportMiniProgram($appID)
    {
        $program = $this->ai->getMiniProgramByID($appID);
        if($program->builtIn === '1') exit;
        $php = $this->ai->createZtAppPhp($appID);
        $zip = $this->ai->createZtAppZip($php);

        header('Content-Type: application/zip');
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: attachment; filename="' . basename($zip) . '"');
        header('Content-Length: ' . filesize($zip));
        readfile($zip);
        unlink($zip);
        exit;
    }
}
