<?php
/**
 * @param string|int $branch
 * @param int $productID
 * @param string $extra
 */
public function setMenu($productID = 0, $branch = '', $extra = '')
{
    return $this->loadExtension('web')->setMenu($productID, $branch, $extra);
}
