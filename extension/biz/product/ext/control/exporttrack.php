<?php
class myProduct extends product
{
    /**
     * 导出矩阵。
     * Export track.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $projectID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $storyType
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function exportTrack($productID, $branch = '', $projectID = 0, $browseType = 'allstory', $param = 0, $storyType = '', $orderBy = 'id_desc')
    {
        if($_POST)
        {
            $trackOrder = "type,grade,{$orderBy}";
            $stories    = $this->productZen->getStories($projectID, $productID, $branch, 0, (int)$param, ($browseType == 'bysearch' ? $storyType : 'all'), $browseType, $trackOrder);
            $tracks     = $this->loadModel('story')->getTracksByStories($stories, $storyType);

            if(empty($tracks)) return $this->send(array('result' => 'fail', 'message' => $this->lang->error->noData));

            $customFields = $this->productZen->getCustomFieldsForTrack($storyType);
            if(isset($tracks['cols']))
            {
                $showFields = $customFields['show'];
                $cols       = array();
                foreach($tracks['cols'] as $col)
                {
                    if(in_array($col['name'], $showFields) || (isset($col['parentName']) && in_array($col['parentName'], $showFields))) $cols[] = $col;
                }
                $tracks['cols'] = $cols;
            }

            $colspan = $rowspan = array();
            $fields  = $this->getExportFields($tracks['cols'], $colspan);
            $rows    = $this->getExportData($tracks, $fields);
            $rows    = $this->processMergeCell($rows, $rowspan);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $rows);
            $this->post->set('colspan', $colspan);
            $this->post->set('rowspan', $rowspan);
            $this->fetch('file', 'export2track', $_POST);
        }

        $fileName = '';
        if($productID)
        {
            $object = $this->loadModel('product')->fetchByID($productID);
        }
        elseif($projectID)
        {
            $object = $this->loadModel('project')->fetchByID($projectID);
        }
        if($object) $fileName = $object->name . ' - ' . $this->lang->product->track;

        $this->view->exportFileTypeList = array('xlsx' => $this->lang->exportFileTypeList['xlsx']);
        $this->view->fileName           = $fileName;

        $this->display();
    }

    /**
     * @param mixed[] $trackCols
     * @param mixed[] $colspan
     */
    public function getExportFields($trackCols, &$colspan)
    {
        if(empty($trackCols)) return array();

        $cols = array();
        foreach($trackCols as $col)
        {
            $fieldName = $col['name'];
            if(isset($col['parentName']))
            {
                $fieldName = $col['parentName'];
                $cols[$fieldName]['children'][] = $col;
            }
            else
            {
                $cols[$fieldName] = $col;
            }
        }

        $fields  = array();
        foreach($cols as $fieldName => $col)
        {
            $isStory   = in_array($fieldName, array('epic', 'requirement', 'story'));
            $subFields = $this->lang->product->exportTrackFields[$fieldName];
            $fields[0][$fieldName] = $col['title'];
            if(isset($col['children']))
            {
                foreach($col['children'] as $childCol)
                {
                    $childFieldName = $childCol['name'];
                    foreach($subFields as $subField => $subFieldName) $fields[1]["{$childFieldName}_{$subField}"] = $subField == 'title' ? $childCol['title'] : $subFieldName;
                }
                $colspan['head'][$fieldName] = count($col['children']) * count($subFields);
            }
            else
            {
                foreach($subFields as $subField => $subFieldName) $fields[1]["{$fieldName}_{$subField}"] = ($isStory && $subField == 'title') ? $col['title'] : $subFieldName;
                if(count($subFields) > 1) $colspan['head'][$fieldName] = count($subFields);
            }
        }
        return $fields;
    }

    /**
     * @param mixed[] $tracks
     * @param mixed[] $fields
     */
    public function getExportData($tracks, $fields)
    {
        $options  = $this->getFieldOptions();
        $cols     = $fields[1];
        $items    = $tracks['items'];
        $projects = array();
        $rows     = array();
        $rootID   = 0;

        $linkProjectFields = array('task', 'execution', 'design', 'commit', 'bug', 'case');
        $storyTypeList     = array('epic', 'requirement', 'story');

        $stories = array();
        foreach($storyTypeList as $storyType)
        {
            foreach($tracks['cols'] as $col)
            {
                $colName = isset($col['parentName']) ? $col['parentName'] : $col['name'];
                if($storyType == $colName && $col['parent'] != '-1') $stories[$col['name']] = null;
            }
        }
        $storyRow = $stories;

        foreach($tracks['lanes'] as $lane)
        {
            $laneName = $lane['name'];
            $storyID  = str_replace('lane_', '', $laneName);
            foreach($tracks['cols'] as $col)
            {
                $colName = $col['name'];
                if(empty($items[$laneName][$colName])) continue;

                if($colName == 'project' || $colName == 'execution')
                {
                    foreach($items[$laneName][$colName] as $project) $projects[$project->id] = $project;
                }
            }

            /* Build each row for link project fields group by project. */
            $linkProjectGroup = array();
            $linkProjectRows  = array();
            while(true)
            {
                $projectID   = null;
                $projectName = '';
                $row         = array();
                foreach($linkProjectFields as $field)
                {
                    if(empty($items[$laneName][$field])) continue;

                    $item        = reset($items[$laneName][$field]);
                    $itemProject = zget($item, 'project', 0);
                    if($projectID === null) $projectID = $itemProject;

                    $row['project'] = zget($projects, $projectID, '');
                    if($field != 'case' && $itemProject != $projectID) continue;

                    if(!empty($item->execution) && isset($projects[$item->execution])) $row['execution'] = $projects[$item->execution];
                    $row[$field] = $item;

                    array_shift($items[$laneName][$field]);
                }

                if(empty($row)) break;
                $linkProjectGroup[$projectID][] = $row;
            }

            /* Merge every project group rows. */
            foreach($linkProjectGroup as $projectRows) $linkProjectRows = array_merge($linkProjectRows, $projectRows);
            if(empty($linkProjectRows)) $linkProjectRows = array(array());

            /* Build story fields on this lane. */
            $reset = false;
            foreach($storyRow as $colName => $story)
            {
                if($reset) $storyRow[$colName] = null;
                if(!empty($items[$laneName][$colName]))
                {
                    $reset = true;
                    $story = reset($items[$laneName][$colName]);

                    if($rootID != $story->root)
                    {
                        $storyRow = $stories;
                        $rootID   = $story->root;
                    }
                    $storyRow[$colName] = $story;
                }
            }

            /* Build track rows by link project rows. */
            foreach($linkProjectRows as $row)
            {
                $trackRow = array();
                foreach($cols as $colKey => $colValue)
                {
                    $position  = strrpos($colKey, '_');
                    $colName   = substr($colKey, 0, $position);
                    $fieldName = substr($colKey, $position + 1);
                    $storyType = '';
                    if(strpos($colName, '_') !== false) list($storyType) = explode('_', $colName);

                    $object = null;
                    if(isset($storyRow[$colName]))
                    {
                        $object = $storyRow[$colName];
                        if(!empty($object) && !empty($storyType)) $trackRow["{$storyType}_{$object->grade}_id"] = $object->id;
                    }

                    if(isset($row[$colName])) $object = $row[$colName];

                    $trackRow[$colKey] = empty($object) ? '' : zget($object, $fieldName, '');
                    if($storyType  && isset($options[$storyType][$fieldName])) $trackRow[$colKey] = zget($options[$storyType][$fieldName], $trackRow[$colKey]);
                    if(!$storyType && isset($options[$colName][$fieldName]))   $trackRow[$colKey] = zget($options[$colName][$fieldName], $trackRow[$colKey]);

                    if($fieldName == 'progress' && !empty($trackRow[$colKey])) $trackRow[$colKey] = (string)(float)$trackRow[$colKey] . '%';
                    if($fieldName == 'delay'    && !empty($trackRow[$colKey])) $trackRow[$colKey] = $this->lang->project->statusList['delay'];
                    if($colKey == 'case_lastRunResult' && empty($trackRow['case_title'])) $trackRow[$colKey] = '';
                }
                $rows[] = $trackRow;
            }
        }
        return $rows;
    }

    public function canFollowMerge($mergeStartType, $mergeStartGrade, $currentType, $currentGrade)
    {
        if($mergeStartType == $currentType && $mergeStartGrade < $currentGrade) return true;
        if($mergeStartType == 'epic' && in_array($currentType, array('requirement', 'story'))) return true;
        if($mergeStartType == 'requirement' && $currentType == 'story') return true;
        return false;
    }

    /**
     * @param mixed[] $rows
     * @param mixed[] $rowspan
     */
    public function processMergeCell($rows, &$rowspan)
    {
        $preRow     = array();
        $firstCol   = array_keys($rows[0])[0];
        $mergeIndex = 0;
        foreach($rows as $i => $row)
        {
            $merged = false;
            foreach($row as $colKey => $value)
            {
                if(strpos($colKey, 'epic') === false && strpos($colKey, 'requirement') === false && strpos($colKey, 'story') === false) continue; // Only merge story fields.

                if(!isset($preRow[$colKey])) $preRow[$colKey] = array();
                list($storyType, $grade, $fieldName) = explode('_', $colKey);
                if($fieldName != 'title') continue;

                $preRowCol = zget($preRow, $colKey, array());
                if(empty($preRowCol) || $preRowCol['value'] != $value)
                {
                    $merged = false;
                    $preRow[$colKey] = array('index' => $i, 'value' => $value);
                    continue;
                }
                if($colKey != $firstCol && !$merged && empty($value))
                {
                    $preRow[$colKey] = array('index' => $i, 'value' => $value);
                    continue;
                }

                if(!empty($prevRow["{$storyType}_{$grade}_id"]) && !empty($row["{$storyType}_{$grade}_id"]) && $row["{$storyType}_{$grade}_id"] != $prevRow["{$storyType}_{$grade}_id"])
                {
                    $merged = false;
                    $preRow[$colKey] = array('index' => $i, 'value' => $value);
                    continue;
                }

                $mergeIndex = $preRowCol['index'];
                if(!isset($rowspan['body'][$mergeIndex][$colKey])) $rowspan['body'][$mergeIndex][$colKey] = 1;
                $rowspan['body'][$mergeIndex][$colKey] += 1;
                $merged = true;
            }

            $prevRow = $row;
        }

        /* Append linked fields. */
        $storyFields = array_keys($preRow);
        $endIndex    = array();
        foreach($rowspan['body'] as $index => $rowSetting)
        {
            foreach($rowSetting as $colKey => $count)
            {
                list($storyType, $storyGrade, $fieldName) = explode('_', $colKey);
                foreach($storyFields as $field)
                {
                    list($type, $grade) = explode('_', $field);
                    if(strpos($field, "{$storyType}_{$storyGrade}") === 0) $rowspan['body'][$index][$field] = $count;
                    if(isset($endIndex[$field]) && $index < $endIndex[$field]) continue;

                    /* Append before fields. */
                    if($storyType == 'epic' && $type == 'epic' && $storyGrade > $grade) $rowspan['body'][$index][$field] = $count;
                    if($storyType == 'requirement')
                    {
                        if($type == 'epic') $rowspan['body'][$index][$field] = $count;
                        if($type == 'requirement' && $storyGrade > $grade) $rowspan['body'][$index][$field] = $count;
                    }
                    if($storyType == 'story')
                    {
                        if($type == 'epic' || $type == 'requirement') $rowspan['body'][$index][$field] = $count;
                        if($type == 'story' && $storyGrade > $grade) $rowspan['body'][$index][$field] = $count;
                    }
                }

                foreach($rowspan['body'][$index] as $field => $count) $endIndex[$field] = $index + $count;
            }

            foreach($rowspan['body'][$index] as $colKey => $count)
            {
                for($i = $index + 1; $i < $endIndex[$colKey]; $i ++) unset($rows[$i][$colKey]);
            }
        }

        return $rows;
    }

    public function getFieldOptions()
    {
        $this->app->loadLang('epic');
        $this->app->loadLang('requirement');
        $this->app->loadLang('project');
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('testcase');

        $users   = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $options = array();
        $options['epic']['pri']           = $this->lang->epic->priList;
        $options['epic']['status']        = $this->lang->epic->statusList;
        $options['epic']['stage']         = $this->lang->epic->stageList;
        $options['requirement']['pri']    = $this->lang->requirement->priList;
        $options['requirement']['status'] = $this->lang->requirement->statusList;
        $options['requirement']['stage']  = $this->lang->requirement->stageList;
        $options['story']['pri']          = $this->lang->story->priList;
        $options['story']['status']       = $this->lang->story->statusList;
        $options['story']['stage']        = $this->lang->story->stageList;
        $options['project']['status']     = $this->lang->project->statusList;
        $options['execution']['status']   = $this->lang->project->statusList;
        $options['task']['pri']           = $this->lang->task->priList;
        $options['task']['status']        = $this->lang->task->statusList;
        $options['task']['assignedTo']    = $users;
        $options['bug']['pri']            = $this->lang->bug->priList;
        $options['bug']['status']         = $this->lang->bug->statusList;
        $options['bug']['assignedTo']     = $users;
        $options['case']['lastRunResult'] = arrayUnion($this->lang->testcase->resultList, array('' => $this->lang->testcase->unexecuted));
        $options['case']['lastRunner']    = $users;

        return $options;
    }
}
