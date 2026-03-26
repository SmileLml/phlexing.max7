<?php
/**
 * @return object|false
 */
public function bind()
{
    return $this->loadExtension('zentaobiz')->bind();
}

/**
 * @param object $data
 */
public function createUser($data)
{
    return $this->loadExtension('zentaobiz')->createUser($data);
}
