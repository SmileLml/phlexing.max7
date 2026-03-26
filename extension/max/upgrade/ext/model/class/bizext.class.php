<?php
class bizextUpgrade extends upgradeModel
{
    /**
     * Record finished task effort.
     *
     * @access public
     * @return bool
     */
    public function recordFinished()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $tasks = $this->dao->select('id,finishedBy,lastEditedBy,finishedDate,lastEditedDate')->from(TABLE_TASK)
            ->where('status')->in('done,closed')
            ->andWhere("(finishedDate='0000-00-00 00:00:00' or lastEditedDate='0000-00-00 00:00:00')")
            ->fetchAll('id');

        $efforts = $this->dao->select('t1.*,t2.date as actionDate')->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_ACTION)->alias('t2')->on('t1.id=t2.objectID')
            ->where('t2.objectType')->eq('effort')
            ->andWhere('t1.left')->eq(0)
            ->andWhere('t1.objectType')->eq('task')
            ->andWhere('t1.objectID')->in(array_keys($tasks))
            ->orderBy('id')
            ->fetchAll('objectID');

        foreach($efforts as $taskID => $effort)
        {
            $data = array();
            if(empty($tasks[$taskID]->finishedBy))   $data['finishedBy']   = $effort->account;
            if(empty($tasks[$taskID]->lastEditedBy)) $data['lastEditedBy'] = $effort->account;
            if(helper::isZeroDate($tasks[$taskID]->finishedDate))   $data['finishedDate']   = $effort->actionDate;
            if(helper::isZeroDate($tasks[$taskID]->lastEditedDate)) $data['lastEditedDate'] = $effort->actionDate;
            if(!empty($data))
            {
                $this->dao->update(TABLE_TASK)->data($data)->where('id')->eq($taskID)->exec();
                $this->saveLogs($this->dao->get());
            }
        }

        return !dao::isError();
    }

    /**
     * Fix repo prefix.
     *
     * @access public
     * @return void
     */
    public function fixRepo()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $this->app->loadConfig('repo');
        $repos = $this->dao->select('*')->from(TABLE_REPO)->fetchAll();
        foreach($repos as $repo)
        {
            if($repo->SCM == 'Subversion')
            {
                $scm = $this->app->loadClass('scm');
                $scm->setEngine($repo);
                $info = $scm->info('');
                $prefix = empty($info->root) ? '' : trim(str_ireplace($info->root, '', str_replace('\\', '/', $repo->path)), '/');
                if($prefix)
                {
                    $prefix = '/' . $prefix;
                    $this->dao->update(TABLE_REPO)->set('prefix')->eq($prefix)->where('id')->eq($repo->id)->exec();
                    $this->saveLogs($this->dao->get());
                }
            }
        }
    }

    /**
     * Fix report for add unique key.
     *
     * @access public
     * @return bool
     */
    public function fixReport()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $reports = $this->dao->select('`code`,count(`code`) as count')->from(TABLE_REPORT)->groupBy('`code`')->orderBy('id')->fetchAll();
        $backSql = '';
        foreach($reports as $report)
        {
            if($report->count == 1) continue;

            $row = $this->dao->select('*')->from(TABLE_REPORT)->where('`code`')->eq($report->code)->orderBy('id')->limit(1)->query()->fetch(PDO::FETCH_ASSOC);

            /* Create key sql for insert. */
            $keys = array_keys($row);
            $keys = array_map('addslashes', $keys);
            $keys = join('`,`', $keys);
            $keys = "`" . $keys . "`";

            /* Create a value sql. */
            $value = array_values($row);
            $value = array_map('addslashes', $value);
            $value = join("','", $value);
            $value = "'" . $value . "'";

            $backSql .= "REPLACE INTO " . TABLE_REPORT . "($keys) VALUES (" . $value . ");\n";
            $this->dao->delete()->from(TABLE_REPORT)->where('id')->eq($row['id'])->exec();
            $this->saveLogs($this->dao->get());
        }

        if(!empty($backSql)) file_put_contents($this->app->getCacheRoot() . 'reportback.sql', $backSql);
        $codeIndex = $this->dao->query("show index from " . TABLE_REPORT . " where `key_name`= 'code'")->fetch();
        if(empty($codeIndex))
        {
            $this->dao->exec("ALTER TABLE " . TABLE_REPORT . " ADD UNIQUE `code` (`code`)");
            $this->saveLogs($this->dao->get());
        }

        return true;
    }

    public function checkURAndSR()
    {
        $hasURAndSR = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=URAndSR');
        if($hasURAndSR) return true;

        $ur = $this->dao->select('*')->from(TABLE_STORY)->where('type')->eq('requirement')->limit(1)->fetch();
        if($ur) $this->setting->setItem('system.common.global.URAndSR', 1);
        return true;
    }

    /**
     * Convert simplified Chinese to traditional Chinese in the report.
     *
     * @access public
     * @return bool
     */
    public function fixReportLang()
    {
        $reportList = $this->dao->select('id,`name`,`desc`')->from(TABLE_REPORT)->fetchAll();
        foreach($reportList as $report)
        {
            if(!empty($report->name))
            {
                $nameList = json_decode($report->name, true);
                $result   = $this::simplifiedtoTraditional($nameList['zh-cn']);
                if($result)
                {
                    $nameList['zh-tw'] = $result;
                    $report->name      = json_encode($nameList);
                }
            }

            if(!empty($report->desc))
            {
                $descList = json_decode($report->desc, true);
                $result   = $this::simplifiedtoTraditional($descList['zh-cn']);
                if($result)
                {
                    $descList['zh-tw'] = $result;
                    $report->desc      = json_encode($descList);
                }
            }
        }

        foreach($reportList as $report)
        {
            $this->dao->update(TABLE_REPORT)->set('name')->eq($report->name)->set('desc')->eq($report->desc)->where('id')->eq($report->id)->exec();
        }
        return true;
    }

    /**
     * Convert simplified characters to traditional characters.
     *
     * @access public
     * @return string
     */
    public static function simplifiedtoTraditional($input)
    {
        include('traditionalchinese.php');
        if(trim($input) == '') return '';

        $simplified  = array_keys($traditionalChinese);
        $traditional = array_values($traditionalChinese);

        $output = str_replace($simplified, $traditional, $input);
        return $output;
    }
}
