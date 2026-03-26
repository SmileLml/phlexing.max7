<?php
public function roadmap($conditions = '')
{
    return $this->loadExtension('zentaopro')->roadmap($conditions);
}

public function productInvest($conditions = '', $productID = 0, $productStatus = 'normal', $productType = 'normal')
{
    return $this->loadExtension('zentaopro')->productInvest($conditions, $productID, $productStatus, $productType);
}

public function testcase($productID = 0)
{
    return $this->loadExtension('zentaopro')->testcase($productID);
}

public function casesrun($productID = 0)
{
    return $this->loadExtension('zentaopro')->casesrun($productID);
}

public function build($productID = 0)
{
    return $this->loadExtension('zentaopro')->build($productID);
}

public function storyLinkedBug($productID = 0, $moduleID = 0)
{
    return $this->loadExtension('zentaopro')->storyLinkedBug($productID, $moduleID);
}

public function workSummary($begin = 0, $end = 0, $dept = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
{
    return $this->loadExtension('zentaopro')->workSummary($begin, $end, $dept, $recTotal, $recPerPage, $pageID);
}

public function workAssignSummary($begin = 0, $end = 0, $dept = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
{
    return $this->loadExtension('zentaopro')->workAssignSummary($begin, $end, $dept, $recTotal, $recPerPage, $pageID);
}

public function bugSummary($dept = 0, $begin = 0 , $end = 0)
{
    return $this->loadExtension('zentaopro')->bugSummary($dept, $begin, $end);
}

public function bugAssignSummary($dept = 0, $begin = 0 , $end = 0)
{
    return $this->loadExtension('zentaopro')->bugAssignSummary($dept, $begin, $end);
}
