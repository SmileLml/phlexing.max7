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
     * Check whether the variable is legal.
     *
     * @param  int    $reportID
     * @param  int    $type
     * @access public
     * @return void
     */
    public function ajaxCheckVar($reportID = 0, $type = '')
    {
        if($_POST and !isset($_POST['sql']))
        {
            $data = fixer::input('post')->remove('copySql,varType')->get();
            foreach($data->varName as $i => $varName)
            {
                if(empty($varName)) die(js::alert($this->lang->crystal->noticeVarName));
                if(empty($data->requestType[$i])) die(js::alert(sprintf($this->lang->crystal->noticeRequestType, $data->varName[$i])));
                if(empty($data->showName[$i])) die(js::alert(sprintf($this->lang->crystal->noticeShowName, $data->showName[$i])));
            }

            $sql = trim($this->post->copySql);
            if($this->post->varType == 'add')
            {
                $result  = $this->report->checkSqlVar($sql);
                $sql .= ' $' . $data->varName[0];
                if($result)
                {
                    $sqlVars = json_decode($this->session->sqlVars);
                    foreach($sqlVars->varName as $i => $varName)
                    {
                        if(!in_array($varName, $result))
                        {
                            unset($sqlVars->varName[$i]);
                            unset($sqlVars->showName[$i]);
                            unset($sqlVars->requestType[$i]);
                            unset($sqlVars->default[$i]);
                            unset($sqlVars->selectList[$i]);
                        }
                    }
                    if($sqlVars and !in_array($data->varName[0], $sqlVars->varName))
                    {
                        $sqlVars->varName[] = $data->varName[0];

                        end($sqlVars->varName);
                        $endKey = key($sqlVars->varName);

                        $sqlVars->requestType[$endKey] = $data->requestType[0];
                        $sqlVars->default[$endKey]     = $data->default[0];
                        $sqlVars->showName[$endKey]    = $data->showName[0];
                        if($data->requestType[0] == 'select') $sqlVars->selectList[$endKey] = $data->selectList[0];
                    }
                }
                else
                {
                    $sqlVars = $data;
                }
            }
            else
            {
                $sqlVars = $data;
            }

            $this->session->set('reportSQL', $sql);
            $this->session->set('sqlVars', json_encode($sqlVars));
            foreach($sqlVars->varName as $varName) $sqlVarValues[$varName] = '';
            $this->session->set('sqlVarValues', serialize($sqlVarValues));

            die(js::locate(inlink('custom', "step=1&reportID=$reportID&from=$type"), 'parent'));
        }
        $sql = trim(stripslashes(trim($this->post->sql)), ';');
        $result = $this->report->checkSqlVar($sql);
        if(empty($result)) die(0);

        if($this->session->sqlVars)
        {
            $sqlVars = json_decode($this->session->sqlVars);
            $varDiff = array_diff($result, $sqlVars->varName);
            if(empty($varDiff) and count($result) == count($sqlVars->varName)) die(0);
        }

        die(json_encode(array_unique($result)));
    }
}
