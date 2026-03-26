<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class meetingBasicInfo extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array
    (
        'meeting'    => '?object',   // 当前会议。
        'users'      => '?array',    // 用户列表。
        'rooms'      => '?array',    // 会议室。
        'depts'      => '?array',    // 部门。
        'typeList'   => '?array',    // 会议类型。
        'project'    => '?object',   // 所属项目。
        'execution'  => '?object',   // 所属迭代。
    );

    protected function getItems()
    {
        global $lang,$config;

        $meeting = $this->prop('meeting', data('meeting'));
        if(!$meeting) return array();

        $users     = $this->prop('users',     data('users'));
        $rooms     = $this->prop('rooms',     data('rooms'));
        $depts     = $this->prop('depts',     data('depts'));
        $typeList  = $this->prop('typeList',  data('typeList'));
        $project   = $this->prop('project',   data('project'));
        $execution = $this->prop('execution', data('execution'));

        $items = array();
        $projectLink = $meeting->project && common::hasPriv('project', 'view') ? helper::createLink('project', 'view', "projectID={$meeting->project}") : '';
        if(!empty($project))
        {
            $items[$lang->meeting->project] = $projectLink ? array
            (
                'control'  => 'link',
                'url'      => $projectLink,
                'text'     => $project->name,
                'title'    => $project->name,
                'data-app' => 'project'
            ) : $project->name;
        }

        $executionLink = $meeting->execution && common::hasPriv('execution', 'view') ? helper::createLink('execution', 'view', "executionID={$meeting->execution}") : '';
        if(!empty($execution) && $execution->multiple)
        {
            $items[$lang->meeting->execution] = $executionLink ? array
            (
                'control'  => 'link',
                'url'      => $executionLink,
                'text'     => $execution->name,
                'title'    => $execution->name,
                'data-app' => 'execution'
            ) : $execution->name;
        }

        $items[$lang->meeting->room] = zget($rooms, $meeting->room, '');
        $items[$lang->meeting->dept] = zget($depts, $meeting->dept, '');
        $items[$lang->meeting->mode] = zget($lang->meeting->modeList, $meeting->mode);
        $items[$lang->meeting->type] = zget($typeList, $meeting->type);

        $objectLink = common::hasPriv($meeting->objectType, 'view') ? helper::createLink($meeting->objectType, 'view', $meeting->objectType . "ID=" . $meeting->objectID) : '';
        if($meeting->objectType and $meeting->objectID)
        {
            $items[$lang->meeting->linked . $config->meeting->objectTypeList[$meeting->objectType]] = $objectLink ? array
            (
                'control'     => 'link',
                'url'         => $objectLink,
                'text'        => $meeting->objectName,
                'title'       => $meeting->objectName,
                'data-toggle' => 'modal',
                'data-size'   => 'lg'
            ) : $meeting->objectName;
        }

        $items[$lang->meeting->host] = zget($users, $meeting->host);

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('bug-basic-info break-all overflow-hidden text-clip'),
            set::items($this->getItems())
        );
    }
}
