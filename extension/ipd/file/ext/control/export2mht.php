<?php
helper::importControl('file');
class myfile extends file
{
    public function export2mht()
    {
        $this->view->fields = $this->post->fields;
        $this->view->rows   = $this->post->rows;
        $this->host         = common::getSysURL();
        $kind               = $this->post->kind;

        switch($kind)
        {
        case 'task':
            foreach($this->view->rows as $row)
            {
                $row->name = html::a($this->host . $this->createLink('task', 'view', "taskID=$row->id"), $row->name, '_blank');
            }
            break;
        case 'story':
            foreach($this->view->rows as $row)
            {
                $row->title= html::a($this->host . $this->createLink('story', 'view', "storyID=$row->id"), $row->title, '_blank');
            }
            break;
        case 'bug':
            foreach($this->view->rows as $row)
            {
                $row->title= html::a($this->host . $this->createLink('bug', 'view', "bugID=$row->id"), $row->title, '_blank');
            }
            break;
        case 'testcase':
            foreach($this->view->rows as $row)
            {
                $row->title= html::a($this->host . $this->createLink('testcase', 'view', "caseID=$row->id"), $row->title, '_blank');
            }
            break;
        }
        $this->view->fileName = $this->post->fileName;

        $output  = "<html xmlns='http://www.w3.org/1999/xhtml'>";
        $output .= "<head>";
        $output .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
        $output .= "<style>table, th, td {font-size: 12px; border: 1px solid gray; border-collapse: collapse;} table th, table td {padding: 5px;}</style>";
        $output .= "<title>{$this->post->fileName}</title>";
        $output .= "</head>";

        $output .= "<body>";
        if($kind == 'task') $output .=  "<div style='color:red'>" . $this->lang->file->childTaskTips . '</div>';

        $output .= $this->buildDownloadTable($this->view->fields, $this->view->rows, $kind);
        $output .= "</body></html>";

        $output = $this->file->getMhtDocument($output, $this->host);

        $this->sendDownHeader($this->post->fileName, 'mht', $output);
    }
}
