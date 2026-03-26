<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'sqlbuildercontrol' . DS . 'v1.php';

class sqlBuilderPicker extends sqlBuilderControl
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        "name?: string",
        "label?: string",
        "items?: array",
        "value?: string",
        "placeholder?: string",
        'labelWidth?: string="80px"',
        'width?: string="60"',
        "suffix?: string",
        'onChange?: function',
        'onInit?: function',
        "error?: bool=false",
        "errorText?: string"
    );

    protected function build()
    {
        $this->setProp('type', 'picker');
        return parent::build();
    }
}
