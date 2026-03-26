<?php
public function getOutsideCompanies()
{
    $companys = $this->dao->select('id, name')->from(TABLE_COMPANY)->where('id')->ne(1)->fetchPairs();

    return arrayUnion(array('' => ''), $companys);
}

public function getCompanyUserPairs()
{
    $pairs = $this->dao->select("t1.account, CONCAT_WS('/', t2.name, t1.realname)")->from(TABLE_USER)->alias('t1')
        ->leftJoin(TABLE_COMPANY)->alias('t2')
        ->on('t1.company = t2.id')
        ->fetchPairs();

    return $pairs;
}
