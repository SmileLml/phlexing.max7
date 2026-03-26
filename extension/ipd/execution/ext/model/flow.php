<?php
/**
 * Get kanban setting.
 *
 * @access public
 * @return object
 */
public function getKanbanSetting()
{
    return $this->loadExtension('flow')->getKanbanSetting();
}

/**
 * Get kanban columns.
 *
 * @param  object $kanbanSetting
 * @access public
 * @return array
 */
public function getKanbanColumns($kanbanSetting)
{
    return $this->loadExtension('flow')->getKanbanColumns($kanbanSetting);
}

/**
 * Get status map of kanban.
 *
 * @param  object $kanbanSetting
 * @access public
 * @return string
 */
public function getKanbanStatusMap($kanbanSetting)
{
    return $this->loadExtension('flow')->getKanbanStatusMap($kanbanSetting);
}

/**
 * Get status list of kanban.
 *
 * @param  object $kanbanSetting
 * @access public
 * @return string
 */
public function getKanbanStatusList($kanbanSetting)
{
    return $this->loadExtension('flow')->getKanbanStatusList($kanbanSetting);
}

/**
 * Get color list of kanban.
 *
 * @param  object $kanbanSetting
 * @access public
 * @return array
 */
public function getKanbanColorList($kanbanSetting)
{
    return $this->loadExtension('flow')->getKanbanColorList($kanbanSetting);
}

/**
 * Get kanban group data.
 *
 * @param  array  $tasks
 * @param  array  $bugs
 * @access public
 * @return array
 * @param mixed[] $stories
 * @param string $type
 */
public function getKanbanGroupData($stories, $tasks = [], $bugs = [], $type = 'story')
{
    return $this->loadExtension('flow')->getKanbanGroupData($stories, $tasks, $bugs, $type);
}

public function getProjectLink($module, $method, $extra)
{
    return $this->loadExtension('flow')->getProjectLink($module, $method, $extra);
}
