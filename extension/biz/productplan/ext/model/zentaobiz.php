<?php
public function getPlansForCharter($productID = 0, $append = '', $branchID = '')
{
    return $this->loadExtension('zentaobiz')->getPlansForCharter($productID, $append, $branchID);
}
