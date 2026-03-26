<?php
/**
 * @return bool|int
 * @param object $case
 */
public function create($case)
{
    return $this->loadExtension('relation')->create($case);
}
/**
 * @return bool|mixed[]
 * @param object $case
 * @param object $oldCase
 * @param mixed[] $testtasks
 */
public function update($case, $oldCase, $testtasks = array())
{
    return $this->loadExtension('relation')->update($case, $oldCase, $testtasks);
}
