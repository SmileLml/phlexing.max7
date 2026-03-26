<?php
public function printFiles($files, $print = true, $field = null, $object = null)
{
    return $this->loadExtension('flow')->printFiles($files, $printm , $field, $object);
}

public function parseExcel($fields = array(), $sheetIndex = 0)
{
    return $this->loadExtension('flow')->parseExcel($fields, $sheetIndex);
}
