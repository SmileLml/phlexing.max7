<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'checklist' . DS . 'v1.php';

class radioList extends checkList
{
    /**
     * @var mixed[]
     */
    protected static $defaultProps = array
    (
        'type' => 'radio'
    );
}
