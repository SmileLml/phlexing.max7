<?php
public function setListValue()
{
    return $this->loadExtension('excel')->setListValue();
}

public function createFromImport()
{
    return $this->loadExtension('excel')->createFromImport();
}
