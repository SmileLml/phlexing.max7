<?php
namespace zin;

class batchActions extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'actionClass?: string=""',
    );

    public static function getPageJS()
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
    {
        return formGroup
        (
            setClass('ml-2'),
            div
            (
                setClass($this->prop('actionClass')),
                btn
                (
                    setClass('ghost add-btn'),
                    set::icon('plus icon-lg'),
                    bind::click('addItem(event)')
                ),
                btn
                (
                    setClass('ghost del-btn'),
                    set::icon('close icon-lg'),
                    bind::click('removeItem(event)')
                )
            )
        );
    }
}
