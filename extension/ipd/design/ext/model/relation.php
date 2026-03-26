<?php
/**
 * @return bool|int
 * @param object $design
 */
public function create($design)
{
    return $this->loadExtension('relation')->create($design);
}
/**
 * @return bool|mixed[]
 * @param int $designID
 * @param object|null $design
 */
public function update($designID = 0, $design = null)
{
    return $this->loadExtension('relation')->update($designID, $design);
}
