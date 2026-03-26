<?php
helper::importControl('effort');
class myeffort extends effort
{
    public function batchCreate($date = 'today', $userID = '', $from = '')
    {
        if(!empty($_POST))
        {
            $this->effort->batchCreate();
            if(dao::isError()) return print(js::error(dao::getError()));

            $locate = $this->createLink('my', 'effort', 'type=all');
            if(isonlybody()) return print(js::closeModal('parent.parent', '', "function(){if(typeof(parent.parent.refreshCalendar) == 'function'){parent.parent.refreshCalendar()}else{parent.parent.location = '$locate';}}"));
            return print(js::locate($locate, 'parent'));
        }
        parent::batchCreate($date, $userID, $from);
    }
}
