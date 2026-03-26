<?php
public function mergeFiles($docID)
{
    return $this->loadExtension('zentaobiz')->mergeFiles($docID);
}

public function diff($text1, $text2)
{
    return $this->loadExtension('zentaobiz')->diff($text1, $text2);
}

public function isImage($text)
{
    return $this->loadExtension('zentaobiz')->isImage($text);
}

public function diffImage($image1, $image2)
{
    return $this->loadExtension('zentaobiz')->diffImage($image1, $image2);
}

/**
 * @param object|bool|null $object
 * @param string $extra
 * @param string $docID
 */
public function checkPrivLib($object, $extra = '', $docID = '')
{
    return $this->loadExtension('zentaobiz')->checkPrivLib($object, $extra, $docID);
}

/**
 * @param object $doc
 * @param bool $checkLib
 */
public function checkPrivDoc($doc, $checkLib = true)
{
    return $this->loadExtension('zentaobiz')->checkPrivDoc($doc);
}

/**
 * @return int|bool
 * @param object $lib
 * @param string $type
 * @param string $libType
 */
public function createLib($lib, $type = '', $libType = '')
{
    return $this->loadExtension('zentaobiz')->createLib($lib, $type, $libType);
}

/**
 * @return mixed[]|bool
 * @param int $libID
 * @param object $lib
 */
public function updateLib($libID, $lib)
{
    return $this->loadExtension('zentaobiz')->updateLib($libID, $lib);
}

/**
 * @param mixed[]|bool $labels
 * @return mixed[]|bool|string
 * @param object $doc
 */
public function create($doc, $labels = false)
{
    return $this->loadExtension('zentaobiz')->create($doc, $labels);
}

/**
 * @return mixed[]|string|bool
 * @param int $docID
 * @param object $doc
 * @param object|null $oldDoc
 */
public function update($docID, $doc, $oldDoc = null)
{
    return $this->loadExtension('zentaobiz')->update($docID, $doc, $oldDoc);
}

public function getAdminCatalog($bookID, $nodeID, $serials)
{
    return $this->loadExtension('zentaobiz')->getAdminCatalog($bookID, $nodeID, $serials);
}

public function computeSN($bookID, $from = 'doc')
{
    return $this->loadExtension('zentaobiz')->computeSN($bookID, $from);
}

public function getChildren($bookID, $nodeID = 0)
{
    return $this->loadExtension('zentaobiz')->getChildren($bookID, $nodeID);
}

public function manageCatalog($bookID, $nodeID)
{
    return $this->loadExtension('zentaobiz')->manageCatalog($bookID, $nodeID);
}

public function getBookStructure($bookID)
{
    return $this->loadExtension('zentaobiz')->getBookStructure($bookID);
}

public function getFrontCatalogItems($bookID, $serials, $articleID = 0)
{
    return $this->loadExtension('zentaobiz')->getFrontCatalogItems($bookID, $serials, $articleID);
}

public function getFrontCatalog($bookID, $serials, $articleID = 0)
{
    return $this->loadExtension('zentaobiz')->getFrontCatalog($bookID, $serials, $articleID);
}

public function sortBookOrder()
{
    return $this->loadExtension('zentaobiz')->sortBookOrder();
}

public function getBookOptionMenu($bookID, $removeRoot = false, $nodeID = 0)
{
    return $this->loadExtension('zentaobiz')->getBookOptionMenu($bookID, $removeRoot, $nodeID);
}

public function fixPath($bookID)
{
    return $this->loadExtension('zentaobiz')->fixPath($bookID);
}

public function getChildModules($parentID)
{
    return $this->loadExtension('zentaobiz')->getChildModules($parentID);
}

public function setDocPOST($docID, $version = 0)
{
    return $this->loadExtension('zentaobiz')->setDocPOST($docID, $version);
}
