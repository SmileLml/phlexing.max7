<?php
/**
 * @return int|false
 * @param object $todo
 */
public function create($todo)
{
    return $this->loadExtension('feedback')->create($todo);
}
