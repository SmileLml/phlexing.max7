<?php
helper::importControl('file');
class myfile extends file
{
    /**
     * Ajax export to mht.
     *
     * @access public
     * @return void
     */
    public function ajaxExport2mht()
    {
        $this->host = common::getSysURL();
        $this->view->fileName = $this->post->fileName;
        $output = $this->post->html;
        $output = $this->file->getMhtDocument($output, $this->host);

        return print($output);
    }
}
