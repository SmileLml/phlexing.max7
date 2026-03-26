# 修改历史记录Bug

## 所属计划

### 修改内容如下：
    1、修改action模块，action目录中对应的config.php文件，修改增加以下内容：
``` 
    $config->action->userFields         = 'openedBy,addedBy,createdBy,editedBy,assignedTo,finishedBy,canceledBy,closedBy,activatedBy,resolvedBy,lastEditedBy,builder,owner,reviewedBy,forwardBy,scriptedBy,manager,commitedBy,archivedBy,PO,QD,RD,feedback,PM,account,changedBy,submitedBy,retractedBy,lastRunner,assignedBy,processedBy,fankuiquerenBy,pmclBy';
    $config->action->multipleUserFields = 'mailto,whitelist,reviewer,users,assignee,approver,PMT,committer,backReviewers,contributor,reviewers,Validator'; 
    $config->action->objectPlan         = 'plan,qiwangjiaofubanben,jihuajiaofubanben';
    $config->action->objectBuild     = 'JieJueBanBenXin,Realizebuild,openedBuild,JiHuaHeRuBanBen,resolveBuild,sjfbbb,resolvedBuild';
    $config->action->objectStory        = 'story';
    $config->action->objectModule       = 'module,ModuleAdd';
    $config->action->objectBug        = 'duplicateBug';
    $config->action->objectJson    = 'Deliver,CustomerProject,CustomerPerception,subStatus';
    $config->action->objectClientPriority = 'ClientPriority';
```

    2、修改action模块，action模块中对应model.php文件，修改内容如下：
```
    elseif(strpos(",{$this->config->action->objectPlan},", ",{$history->field},") !== false)
        {
            if(!empty($history->old))
            {
                $history->oldValue = $this->dao->select('title')->from(TABLE_PRODUCTPLAN)->where('id')->eq($history->old)->fetch('title');
                $history->oldValue = trim($history->oldValue, ',');
            }

            if(!empty($history->new))
            {
                $history->newValue = $this->dao->select('title')->from(TABLE_PRODUCTPLAN)->where('id')->eq($history->new)->fetch('title');
                $history->newValue = trim($history->newValue, ',');
            }
        }
        elseif(strpos(",{$this->config->action->objectStory},", ",{$history->field},") !== false)
        {
            if(!empty($history->old))
            {
                $history->oldValue = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($history->old)->fetch('title');
                $history->oldValue = trim($history->oldValue, ',');
            }

            if(!empty($history->new))
            {
                $history->newValue = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($history->new)->fetch('title');
                $history->newValue = trim($history->newValue, ',');
            }
        }
        elseif(strpos(",{$this->config->action->objectModule},", ",{$history->field},") !== false)
        {
            if(!empty($history->old))
            {
                $history->oldValue = $this->dao->select('name')->from(TABLE_MODULE)->where('id')->eq($history->old)->fetch('name');
                $history->oldValue = trim($history->oldValue, ',');
            }

            if(!empty($history->new))
            {
                $history->newValue = $this->dao->select('name')->from(TABLE_MODULE)->where('id')->eq($history->new)->fetch('name');
                $history->newValue = trim($history->newValue, ',');
            }
        }
        elseif(strpos(",{$this->config->action->objectBug},", ",{$history->field},") !== false)
        {
            if(!empty($history->old))
            {
                $history->oldValue = $this->dao->select('title')->from(TABLE_BUG)->where('id')->eq($history->old)->fetch('title');
                $history->oldValue = trim($history->oldValue, ',');
            }

            if(!empty($history->new))
            {
                $history->newValue = $this->dao->select('title')->from(TABLE_BUG)->where('id')->eq($history->new)->fetch('title');
                $history->newValue = trim($history->newValue, ',');
            }
        }
        elseif(strpos(",{$this->config->action->objectBuild},", ",{$history->field},") !== false)
        {
            if(!empty($history->old))
            {
                $history->oldValue = '';
                $oldValues = explode(',', $history->old);
                $result = $this->dao->select('name')->from(TABLE_BUILD)->where('id')->in($oldValues)->fetchAll();
                $names = array_column($result, 'name');
                $history->oldValue = implode(',', $names);
            }

            if(!empty($history->new))
            {
                $history->newValue = '';
                $newValues = explode(',', $history->new);
                $result = $this->dao->select('name')->from(TABLE_BUILD)->where('id')->in($newValues)->fetchAll();
                $names = array_column($result, 'name');
                $history->newValue = implode(',', $names);
            }
        }
        elseif(strpos(",{$this->config->action->objectJson},", ",{$history->field},") !== false)
        {
            $objects = $this->dao->select('datasource')->from(TABLE_WORKFLOWDATASOURCE)->where('code')->eq($history->field)->fetch('datasource');
            $keysToFind = ['jh', 'yjj', 'ygb', 'notbug', 'pending', 'yyz'];
            if(!empty($objects))
            {
                $object = json_decode($objects, true);
            } else {
                $objects = $this->dao->select('options')->from(TABLE_WORKFLOWFIELD)->where('field')->eq($history->field)->andWhere('module')->eq('bug')->fetch('options');
                $object = json_decode($objects, true);

            }
            if(!empty($history->old))
            {
                if(in_array($history->old, $keysToFind))
                {
                    $history->oldValue = $this->getOptionValue($object, $history->old);
                }else {
                    $history->oldValue = $object[$history->old];
                    $history->oldValue = trim($history->oldValue, ',');
                }

            }

            if(!empty($history->new))
            {
                if(in_array($history->new, $keysToFind))
                {
                    $history->newValue = $this->getOptionValue($object, $history->new);
                }else {
                    $history->newValue = $object[$history->new];
                    $history->newValue = trim($history->newValue, ',');
                }
            }
        }
        elseif(strpos(",{$this->config->action->objectClientPriority},", ",{$history->field},") !== false)
        {
            $map = [
                '1' => '高',
                '2' => '中',
                '3' => '低'
            ];
            if(!empty($history->old))
            {
                $history->oldValue = $map[$history->old];
            }

            if(!empty($history->new))
            {
                $history->newValue = $map[$history->new];
            }
        }
```
### 将修改的原文件与修改后的文件都保存在git上，保证代码代码能够及时恢复