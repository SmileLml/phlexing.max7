<?php
/**
 * @param int $taskID
 * @param int $productID
 */
public function getBugInfo($taskID, $productID)
{
    return $this->loadExtension('export')->getBugInfo($taskID, $productID);
}
