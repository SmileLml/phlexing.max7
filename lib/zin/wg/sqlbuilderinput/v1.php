<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'sqlbuildercontrol' . DS . 'v1.php';

class sqlBuilderInput extends sqlBuilderControl
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        "name?: string",
        "label?: string",
        "value?: string",
        "placeholder?: string",
        'labelWidth?: string="80px"',
        'width?: string="60"',
        "suffix?: string",
        'onChange?: function',
        "error?: bool=false",
        "errorText?: string"
    );

    protected function build()
    {
        $this->setProp('type', 'input');
        return parent::build();
    }
}
