<?php
class zentaomaxAction extends actionModel
{
    /**
     * 将交付物配置项渲染成可读的变更记录。
     * Process deliverable json.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  object $history
     * @access public
     * @return string
     */
    public function processDeliverableJson($objectType, $objectID, $history)
    {
        $content         = '';
        static $docList  = array();
        static $fileList = array();
        if(empty($docList) && empty($fileList))
        {
            $docList = $this->dao->select('id,title')->from(TABLE_DOC)->where('1=1')
                ->beginIF($objectType == 'project')->andWhere('project')->eq($objectID)->fi()
                ->beginIF($objectType == 'execution')->andWhere('execution')->eq($objectID)->fi()
                ->fetchPairs();

            $fileList = $this->dao->select('id,title')->from(TABLE_FILE)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->fetchPairs();
        }
        $oldDeliverables = json_decode($history->old, true);
        $newDeliverables = json_decode($history->new, true);

        if(empty($oldDeliverables)) return '';

        foreach($oldDeliverables as $typeCode => $methodGroup)
        {
            foreach($methodGroup as $methodCode => $deliverables)
            {
                foreach($deliverables as $key => $oldDeliverable)
                {
                    if(empty($newDeliverables[$typeCode][$methodCode])) continue;
                    foreach($newDeliverables[$typeCode][$methodCode] as $index => $newDeliverable)
                    {
                        if($oldDeliverable['deliverable'] != $newDeliverable['deliverable']) continue;

                        if(empty($oldDeliverable['doc']) && !empty($newDeliverable['doc']))
                        {
                            $content .= sprintf($this->lang->action->desc->addDeliverable, $newDeliverable['category'], zget($docList, $newDeliverable['doc']));
                        }
                        elseif(!empty($oldDeliverable['doc']) && empty($newDeliverable['doc']))
                        {
                            $content .= sprintf($this->lang->action->desc->removeDeliverable, $oldDeliverable['category'], zget($docList, $oldDeliverable['doc']));
                        }
                        elseif(!empty($oldDeliverable['doc']) && !empty($newDeliverable['doc']) && $oldDeliverable['doc'] != $newDeliverable['doc'])
                        {
                            $content .= sprintf($this->lang->action->desc->changeDeliverable, $oldDeliverable['category'], zget($docList, $oldDeliverable['doc']), zget($docList, $newDeliverable['doc']));
                        }

                        if(empty($oldDeliverable['file']) && !empty($newDeliverable['file']))
                        {
                            $content .= sprintf($this->lang->action->desc->addDeliverable, $newDeliverable['category'], zget($fileList, $newDeliverable['file']));
                        }
                        elseif(!empty($oldDeliverable['file']) && empty($newDeliverable['file']))
                        {
                            $content .= sprintf($this->lang->action->desc->removeDeliverable, $oldDeliverable['category'], zget($fileList, $oldDeliverable['file']));
                        }
                        elseif(!empty($oldDeliverable['file']) && !empty($newDeliverable['file']) && $oldDeliverable['file'] != $newDeliverable['file'])
                        {
                            $content .= sprintf($this->lang->action->desc->changeDeliverable, $oldDeliverable['category'], zget($fileList, $oldDeliverable['file']), zget($fileList, $newDeliverable['file']));
                        }
                        unset($deliverables[$key]);
                        unset($newDeliverables[$typeCode][$methodCode][$index]);
                        break;
                    }
                }

                /* 如果旧交付物配置存在但是新交付物配置不存在了。 */
                foreach($deliverables as $oldDeliverable)
                {
                    if(!empty($oldDeliverable['doc']))  $content .= sprintf($this->lang->action->desc->removeDeliverable, $oldDeliverable['category'], zget($docList, $oldDeliverable['doc']));
                    if(!empty($oldDeliverable['file'])) $content .= sprintf($this->lang->action->desc->removeDeliverable, $oldDeliverable['category'], zget($fileList, $oldDeliverable['file']));
                }

                /* 如果旧交付物配置不存在但是新交付物配置增加了。 */
                if(!empty($newDeliverables[$typeCode][$methodCode]))
                {
                    foreach($newDeliverables[$typeCode][$methodCode] as $newDeliverable)
                    {
                        if(!empty($newDeliverable['doc']))  $content .= sprintf($this->lang->action->desc->addDeliverable, $newDeliverable['category'], zget($docList, $newDeliverable['doc']));
                        if(!empty($newDeliverable['file'])) $content .= sprintf($this->lang->action->desc->addDeliverable, $newDeliverable['category'], zget($fileList, $newDeliverable['file']));
                    }
                }
            }
        }
        return $content;
    }
}
