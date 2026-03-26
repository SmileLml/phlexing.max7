<?php
/**
 * @param int $id
 */
public function getRelationByID($id)
{
    return $this->loadExtension('relation')->getRelationByID($id);
}

/**
 * @param int $excludedID
 */
public function getAllRelationName($excludedID = 0)
{
    return $this->loadExtension('relation')->getAllRelationName($excludedID);
}

/**
 * @param mixed[] $formData
 */
public function createRelation($formData)
{
    $this->loadExtension('relation')->createRelation($formData);
}

/**
 * @param int $id
 * @param mixed[] $formData
 */
public function editRelation($id, $formData)
{
    return $this->loadExtension('relation')->editRelation($id, $formData);
}

/**
 * @param int $key
 */
public function getRelationObjectCount($key = 0)
{
    return $this->loadExtension('relation')->getRelationObjectCount($key);
}

/**
 * @param string $objectType
 * @param string $browseType
 * @param string $orderBy
 * @param object|null $pager
 * @param int $excludedID
 */
public function getObjects($objectType, $browseType = '', $orderBy = 'id_desc', $pager = null, $excludedID = 0)
{
    return $this->loadExtension('relation')->getObjects($objectType, $browseType, $orderBy, $pager, $excludedID);
}

/**
 * @param string $objectType
 */
public function getObjectCols($objectType)
{
    return $this->loadExtension('relation')->getObjectCols($objectType);
}

/**
 * @param bool $getParis
 * @param bool $addDefault
 */
public function getRelationList($getParis = false, $addDefault = false)
{
    return $this->loadExtension('relation')->getRelationList($getParis, $addDefault);
}

/**
 * @param int $objectID
 * @param string $objectType
 * @param mixed[] $objectRelation
 * @param string $relatedObjectType
 */
public function relateObject($objectID, $objectType, $objectRelation, $relatedObjectType)
{
    return $this->loadExtension('relation')->relateObject($objectID, $objectType, $objectRelation, $relatedObjectType);
}

/**
 * @param int $objectID
 * @param string $objectType
 * @param string $relationName
 * @param int $relatedObjectID
 * @param string $relatedObjectType
 */
public function removeObjects($objectID, $objectType, $relationName, $relatedObjectID, $relatedObjectType)
{
    return $this->loadExtension('relation')->removeObjects($objectID, $objectType, $relationName, $relatedObjectID, $relatedObjectType);
}

/**
 * @param mixed[] $objectList
 */
public function getObjectInfoByType($objectList)
{
    return $this->loadExtension('relation')->getObjectInfoByType($objectList);
}

/**
 * @param int|mixed[] $objectID
 * @return mixed[]|int
 * @param string $objectType
 * @param string $browseType
 * @param bool $getCount
 */
public function getRelatedObjectList($objectID, $objectType, $browseType = 'byRelation', $getCount = false)
{
    return $this->loadExtension('relation')->getRelatedObjectList($objectID, $objectType, $browseType, $getCount);
}

public function setConfig4Workflow()
{
    return $this->loadExtension('relation')->setConfig4Workflow();
}
