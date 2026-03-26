<?php
class myScreen extends control
{
    /**
     * Delete screen.
     *
     * @param  int    $screenID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($screenID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->screen->confirmDelete, inlink('delete', "screenID=$screenID&confirm=yes")));
        }
        else
        {
            $screen = $this->screen->getByID($screenID);
            if($screen->builtin) return print(js::locate(inlink('browse'), 'parent'));

            $this->screen->delete(TABLE_SCREEN, $screenID);
        }
        return print(js::locate(inlink('browse'), 'parent'));
    }
}
