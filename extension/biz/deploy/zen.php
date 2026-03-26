<?php
/**
 * The zen file of deploy module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
class deployZen extends deploy
{
    /**
     * 处理步骤数据格式。
     * Process step data format.
     *
     * @param  array     $stepList
     * @access protected
     * @return array
     */
    protected function processSteps($stepList)
    {
        $steps         = array();
        $preGrade      = 1;
        $parentSteps   = array();
        $key           = array(0, 0, 0);
        foreach($stepList as $step)
        {
            $parentSteps[$step->id] = $step->parent;
            $grade = 1;
            if(isset($parentSteps[$step->parent])) $grade = isset($parentSteps[$parentSteps[$step->parent]]) ? 3 : 2;
            if(isset($steps[$step->parent])) $preGrade = $steps[$step->parent]->grade;

            if($grade > $preGrade)
            {
                $key[$grade - 1] = 1;
            }
            else
            {
                if($grade < $preGrade)
                {
                    if($grade < 2) $key[1] = 0;
                    if($grade < 3) $key[2] = 0;
                }
                $key[$grade - 1] ++;
            }
            if(isset($steps[$step->parent]) && $grade == 2) $key[0] = $steps[$step->parent]->name;

            $name = implode('.', $key);
            $data = new stdclass();
            $data->name   = str_replace('.0', '', $name);
            $data->id     = $step->id;
            $data->step   = $step->title;
            $data->desc   = $step->title;
            $data->expect = $step->content;
            $data->parent = $step->parent;
            $data->grade  = $grade;
            $steps[$step->id] = $data;

            $preGrade = $grade;
        }

        return $steps;
    }

    /**
     * 检查新增和编辑表单提交的合法性。
     * Check formData of create and edit.
     *
     * @param  object    $formData
     * @access protected
     * @return bool
     */
    protected function checkFormData($formData)
    {
        if(mb_strlen($formData->name) > 50)
        {
            dao::$errors['name'] = $this->lang->deploy->notice->nameLength;
        }

        if(!preg_match('/^(\w|[\x4e00-\x9fa5]|-)+$/u', $formData->name))
        {
            dao::$errors['name'] = $this->lang->deploy->notice->styleName;
        }

        if(mb_strlen(strip_tags($formData->desc)) > 255)
        {
            dao::$errors['desc'] = $this->lang->deploy->notice->descLength;
        }

        return !dao::isError();
    }
}
