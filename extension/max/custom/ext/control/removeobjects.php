<?php
class custom extends control
{
    /**
     * 移除关联对象。
     * Remove objects.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  string $relationName
     * @param  int    $relatedObjectID
     * @param  string $relatedObjectType
     * @access public
     * @return bool
     */
    public function removeObjects($objectID, $objectType, $relationName, $relatedObjectID, $relatedObjectType)
    {
        $this->custom->removeObjects($objectID, $objectType, $relationName, $relatedObjectID, $relatedObjectType);
        if(dao::isError()) return $this->sendError(array('message' => dao::getError()));
        return $this->sendSuccess(array('load' => true));
    }
}
