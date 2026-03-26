<?php
namespace zin;

requireWg('thinkStepBase');

class thinkNode  extends thinkStepBase
{
    /**
     * @var mixed[]
     */
    protected static $defaultProps = array
    (
        'type' => 'node'
    );
}
