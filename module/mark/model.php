<?php
/**
 * The model file of mark module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xinzhi Qi <qixinzhi@chandao.com>
 * @package     mail
 * @link        https://www.zentao.net
 */
?>
<?php
class markModel extends model
{
    /**
     * 获取需要标记的对象。
     * Get needed mark sobjects.
     *
     * @param  array  $objectIDs
     * @param  string $objectType
     * @param  string $version
     * @param  string $mark
     * @access public
     * @return array
     */
    public function getNeededMarks($objectIDs, $objectType, $version, $mark)
    {
        return $this->dao->select('objectID, version')->from(TABLE_MARK)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->in($objectIDs)
            ->beginIF($version != 'all')->andWhere('version')->eq($version)->fi()
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('mark')->eq($mark)
            ->fetchAll();
    }

    /**
     * @param mixed[] $objects
     * @param string $objectType
     * @param string $mark
     */
    public function getMarks($objects, $objectType, $mark)
    {
        $objectIDs = array_column($objects, 'id');
        $marks     = $this->getNeededMarks($objectIDs, $objectType, 'all', $mark);

        foreach($objects as $object)
        {
            $objectMarks = array_filter($marks, function($mark) use($object)
            {
                return $mark->objectID == $object->id && $mark->version == $object->version;
            });

            $object->mark = !empty($objectMarks);
        }

        return $objects;
    }

    /**
     * @param string $objectType
     * @param int $objectID
     * @param string $version
     * @param string $mark
     */
    public function isMark($objectType, $objectID, $version, $mark = 'view')
    {
        return $this->dao->select('*')->from(TABLE_MARK)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->in($objectID)
            ->andWhere('version')->eq($version)
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('mark')->eq($mark)
            ->fetchAll();
    }

    /**
     * Judge has mark.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $version
     * @param  string $mark
     * @param  bool   $onlyMajor
     * @access public
     * @return bool
     */
    public function hasMark($objectType, $objectID, $version = 'all', $mark = 'view', $onlyMajor = false)
    {
        $mark = $this->dao->select('id')->from(TABLE_MARK)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->in($objectID)
            ->beginIF($version != 'all')->andWhere('version')->eq($version)->fi()
            ->beginIF($onlyMajor)->andWhere('version')->notlike('%.%')->fi()
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('mark')->eq($mark)
            ->fetch();
        return !empty($mark);
    }

    /**
     * 设置对象的标记。
     * Set object marks.
     *
     * @param  array  $objectIDs
     * @param  string $objectType
     * @param  string $version
     * @param  string $mark
     * @param  string $extra
     * @access public
     * @return bool
     */
    public function setMark($objectIDs, $objectType, $version, $mark, $extra = '')
    {
        $data = new stdclass();
        $data->objectType = $objectType;
        $data->version    = $version;
        $data->account    = $this->app->user->account;
        $data->mark       = $mark;
        $data->extra      = $extra;
        $data->date       = helper::now();

        foreach($objectIDs as $objectID)
        {
            $data->objectID = $objectID;
            $this->dao->insert(TABLE_MARK)->data($data)->autocheck()->exec();
        }

        return dao::isError();
    }
}
