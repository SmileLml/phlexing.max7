<?php
/**
 * The control file of report module of zentaopms.
 *
 * @copyright   copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     zpl (http://zpl.pub/page/zplv12.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     report
 * @link        https://www.zentao.net
 */
helper::importControl('report');
class myReport extends report
{
    /**
     * Save report.
     *
     * @param  int    $reportID
     * @param  int    $step
     * @param  string $type
     * @param  string $from
     * @access public
     * @return void
     */
    public function saveReport($reportID = 0, $step = 2, $type = '', $from = '')
    {
        if($_POST or $reportID)
        {
            $data = fixer::input('post')
                ->join('module', ',')
                ->add('sql', $this->session->reportSQL)
                ->add('step', (int)$step)
                ->add('params', $step == 2 ? $this->session->reportParams : '')
                ->add('vars', $this->session->sqlVars)
                ->add('langs', $this->session->sqlLangs)
                ->add('addedBy', $this->app->user->account)
                ->add('addedDate', helper::now())
                ->skipSpecial('sql,params,vars,langs')
                ->remove('desc,name')
                ->get();

            if(isset($_POST['name']))
            {
                $names = $this->post->name;
                $checkName = false;
                foreach($names as $langKey => $name)
                {
                    $name = trim($name);
                    if(!empty($name)) $checkName = true;
                    $names[$langKey] = htmlspecialchars($name);
                }
                if(!$checkName) return print(js::alert($this->lang->crystal->emptyName));
                $data->name = json_encode($names);
            }

            if(isset($_POST['desc']))
            {
                $descs = $this->post->desc;
                foreach($descs as $langKey => $desc) $descs[$langKey] = htmlspecialchars($desc);
                $data->desc = json_encode($descs);
            }

            if($step == 0) return print(js::alert($this->lang->crystal->noStep));
            $result = $this->report->checkSqlVar($data->sql);
            if(!$result)
            {
                $data->vars = '';
            }
            elseif(empty($data->vars))
            {
                return print(js::alert($this->lang->crystal->errorSave));
            }
            if($step == 2 and empty($data->params)) return print(js::alert(sprintf($this->lang->error->notempty, $this->lang->crystal->params)));

            if($reportID)
            {
                unset($data->addedBy);
                unset($data->addedDate);
                $this->dao->update(TABLE_REPORT)->data($data)->autocheck()->batchCheck('sql', 'notempty')->where('id')->eq($reportID)->exec();

                if(dao::isError()) return print(js::error(dao::getError()));
                return print(js::reload('parent'));
            }
            else
            {
                $this->lang->report->code = $this->lang->crystal->code;
                $this->dao->insert(TABLE_REPORT)->data($data)->autocheck()
                    ->batchCheck('code,sql', 'notempty')
                    ->check('code', 'unique')
                    ->exec();

                if(dao::isError()) return print(js::error(dao::getError()));
                $reportID = $this->dao->lastInsertId();
                return print(js::locate(inlink('custom', "step=$step&reportID=$reportID&from=$from"), 'parent.parent'));
            }
        }

        $this->view->type = $type;
        $this->display();
    }
}
