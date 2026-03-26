<?php
/**
 * The model file of reviewsetting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     reviewsetting
 * @version     $Id: model.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class reviewsettingModel extends model
{
    /**
     * Get version rules by object.
     *
     * @param string $object //Object type. (stage|story|task|bug|cases|design)
     * @access public
     * @return string
     */
    public function getVersionName($object = '')
    {
        if($object)
        {
            $owner   = 'system';
            $module  = 'company';
            $section = 'version';
            $version = '';

            $setting = $this->loadModel('setting');
            $result  = $setting->getItem("owner={$owner}&module={$module}&section={$section}&key={$object}");
            if($result)
            {
                $result = json_decode($result);
                foreach ($result->unit as $unit)
                {
                    switch ($unit[0])
                    {
                        case 'date1':
                            $unit[0]  = date('Ymd');
                            $version .= implode('', $unit);
                            break;
                        case 'date2':
                            $unit[0]  = date('Y-m-d');
                            $version .= implode('', $unit);
                            break;
                        case 'fixed':
                            unset($unit[0]);
                            $version .= implode('', $unit);
                            break;
                        case 'user':
                            $unit[0]  = $this->app->user->account;
                            $version .= implode('', $unit);
                            break;
                    }
                }

                /* Append the version number. */
                $commit   = $this->getSerialNumber($object) + 1;
                $result->padding ? $commit = sprintf("%02d", $commit) : $commit;
                $version .= $commit;
            }
            else
            {
                $commit   = $this->getSerialNumber($object) + 1;
                $version .= sprintf("%02d", $commit);
            }

            return $version;
        }
    }

    /**
     * Gets the number of commits of the object today.
     *
     * @param string $object
     * @access public
     * @return int
     */
    public function getSerialNumber($object)
    {
        return $this->dao->select('COUNT(1) AS sum')->from(TABLE_OBJECT)
            ->where('category')->eq($object)
            ->andWhere('type')->eq('reviewed')
            ->andWhere('createdDate')->eq(date('Y-m-d'))
            ->fetch('sum');
    }
}
