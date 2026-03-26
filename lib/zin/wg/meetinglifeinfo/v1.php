<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class meetingLifeInfo extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array
    (
        'meeting'  => '?object', // 当前会议。
        'users'    => '?array'   // 用户列表。
    );

    protected function getItems()
    {
        global $lang;

        $meeting = $this->prop('meeting', data('meeting'));
        if(!$meeting) return array();

        $users  = $this->prop('users',  data('users'));

        $items  = array();

        $items[$lang->meeting->minutedBy]   = zget($users, $meeting->minutedBy);
        $items[$lang->meeting->minutedDate] = helper::isZeroDate($meeting->minutedDate) ? '' : $meeting->minutedDate;
        $items[$lang->meeting->createdBy]   = zget($users, $meeting->createdBy);
        $items[$lang->meeting->createdDate] = $meeting->createdDate;
        $items[$lang->meeting->editedBy]    = zget($users, $meeting->editedBy);
        $items[$lang->meeting->editedDate]  = helper::isZeroDate($meeting->editedDate) ? '' : $meeting->editedDate;

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('meeting-life-info'),
            set::items($this->getItems())
        );
    }
}
