<?php
namespace zin;

requireWg('thinkStepBase');

class thinkTransition  extends thinkStepBase
{
    /**
     * @var mixed[]
     */
    protected static $defaultProps = array
    (
        'type' => 'transition'
    );
}
