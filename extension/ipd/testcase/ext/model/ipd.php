<?php
/**
 * 追加 bug 和用例执行结果信息
 * Append bugs and results.
 *
 * @param  array  $cases
 * @param  string $type
 * @param  array  $caseIdList
 * @access public
 * @return void
 */
public function appendData($cases, $type = 'case', $caseIdList = array())
{
    $cases = parent::appendData($cases, $type, $caseIdList);
    return $this->loadModel('story')->getAffectObject($cases, 'case');
}
