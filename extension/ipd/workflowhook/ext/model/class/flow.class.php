<?php
class flowWorkflowhook extends workflowhookModel
{
    public function getTableFields($table)
    {
        $fields = parent::getTableFields($table);
        if($table == 'storyspec')
        {
            $this->app->loadLang('story');
            foreach($fields as $field => $name)
            {
                if(isset($this->lang->story->{$field}) and $field == $name) $fields[$field] = $this->lang->story->{$field};
            }
        }

        return $fields;
    }
}
