<?php
/**
 * 创建执行风险看板列。
 * Create execution risk columns.
 *
 * @param  int    $laneID
 * @param  string $type
 * @param  int    $executionID
 * @access public
 * @return void
 */
public function createExecutionRiskColumns($laneID, $type, $executionID)
{
    $columns = $this->lang->kanban->riskColumn;
    if(empty($columns)) return;

    foreach($columns as $colType => $name)
    {
        $data = new stdclass();
        $data->name   = $name;
        $data->color  = '#333';
        $data->type   = $colType;
        $data->region = 0;

        $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();

        $colID = $this->dao->lastInsertId();
        $this->addKanbanCell($executionID, $laneID, $colID, $type);
    }
}

/**
 * 向风险卡片中添加字段。
 * Append risk field to cardsData.
 *
 * @param  array  $cardsData
 * @param  array  $risks
 * @access public
 * @return array
 */
public function appendRiskField($cardsData, $risks)
{
    $this->loadModel('risk');
    $priLevel = array('high' => 1, 'middle' => 2, 'low' => 3);
    foreach($cardsData as $laneID => $dataGroup)
    {
        foreach($dataGroup as $groupID => $datas)
        {
            foreach($datas as $index => $data)
            {
                $data['priLevel'] = zget($priLevel, $data['pri'], 3);
                $data['pri']      = zget($this->lang->risk->priList, $data['pri'], $data['pri']);
                $strategy         = $risks[$data['id']]->strategy;
                $data['strategy'] = $strategy ? ($this->lang->risk->strategy . ":  " . zget($this->lang->risk->strategyList, $strategy)) : '';

                $datas[$index] = $data;
            }

            $dataGroup[$groupID] = $datas;
        }

        $cardsData[$laneID] = $dataGroup;
    }

    return $cardsData;
}

/**
 * 更新专业研发看板中的风险泳道上的卡片。
 * Update card of risk lane in RD kanban.
 *
 * @param  array  $cardPairs
 * @param  int    $executionID
 * @param  string $otherCardList
 * @access public
 * @return array
 */
public function refreshRiskCards($cardPairs, $executionID, $otherCardList)
{
    $risks = $this->loadModel('risk')->getKanbanRisks($executionID, explode(',', $otherCardList));
    foreach($risks as $riskID => $risk)
    {
        foreach($this->config->kanban->riskColumnStatusList as $colType => $status)
        {
            if(!isset($cardPairs[$colType])) continue;

            if($risk->status != $status and strpos($cardPairs[$colType], ",$riskID,") !== false)
            {
                $cardPairs[$colType] = str_replace(",$riskID,", ',', $cardPairs[$colType]);
            }

            if(strpos(',active,track,', $colType) !== false) continue;

            if($risk->status == $status and strpos($cardPairs[$colType], ",$riskID,") === false)
            {
                $cardPairs[$colType] = empty($cardPairs[$colType]) ? ",$riskID," : ",$riskID". $cardPairs[$colType];
            }
        }

        if($risk->status == 'active' and strpos($cardPairs['active'], ",$riskID,") === false and strpos($cardPairs['track'], ",$riskID,") === false)
        {
            $cardPairs['active'] = empty($cardPairs['active']) ? ",$riskID," : ",$riskID" . $cardPairs['active'];
        }
    }

    return $cardPairs;
}

/**
 * Copy code from kanban control.php ajaxMoveCard function。
 *
 * @param  array  $outputs
 * @access public
 * @return void
 */
public function moveRiskCard($outputs)
{
    $cardID      = (int)$outputs['objectID'];
    $executionID = (int)$outputs['execution'];
    $fromColID   = (int)$outputs['fromColID'];
    $toColID     = (int)$outputs['toColID'];
    $fromLaneID  = $toLaneID = (int)$outputs['laneID'];
    $browseType  = $outputs['browseType'];
    $groupBy     = $outputs['groupBy'];

    $this->loadModel('kanban');
    $fromCell = $this->getExecutionFromCell($cardID, $executionID, $fromColID, $fromLaneID, $groupBy, $browseType);
    $toCell   = $this->getExecutionToCell($executionID, $toColID, $toLaneID);

    $fromCards = str_replace(",$cardID,", ',', $fromCell->cards);
    $fromCards = $fromCards == ',' ? '' : $fromCards;
    $toCards   = ',' . implode(',', array_unique(array_filter(explode(',', $toCell->cards)))) . ",$cardID,";
    $this->updateExecutionCell($executionID, $fromColID, $fromLaneID, $fromCards);
    $this->updateExecutionCell($executionID, $toColID, $toLaneID, $toCards);
}

/**
 * 获取执行下看板的来源看板Cell。
 * Get source kanban cell of execution.
 *
 * @param  int    $cardID
 * @param  int    $executionID
 * @param  int    $fromColID
 * @param  int    $fromLaneID
 * @param  string $groupBy
 * @param  string $browseType
 * @access public
 * @return object
 */
protected function getExecutionFromCell($cardID, $executionID, $fromColID, $fromLaneID, $groupBy, $browseType)
{
    return $this->dao->select('id, cards, lane')->from(TABLE_KANBANCELL)
        ->where('kanban')->eq($executionID)
        ->andWhere('`column`')->eq($fromColID)
        ->beginIF(!$groupBy or $groupBy == 'default')->andWhere('lane')->eq($fromLaneID)->fi()
        ->beginIF($groupBy and $groupBy != 'default')
        ->andWhere('type')->eq($browseType)
        ->andWhere('cards')->like("%,$cardID,%")
        ->fi()
        ->fetch();
}

/**
 * 获取执行下看板的目标看板Cell。
 * Get target kanban cell of execution.
 *
 * @param  int    $executionID
 * @param  int    $toColID
 * @param  int    $toLaneID
 * @access public
 * @return object
 */
protected function getExecutionToCell($executionID, $toColID, $toLaneID)
{
    return $this->dao->select('id, cards')->from(TABLE_KANBANCELL)
        ->where('kanban')->eq($executionID)
        ->andWhere('lane')->eq($toLaneID)
        ->andWhere('`column`')->eq($toColID)
        ->fetch();
}
