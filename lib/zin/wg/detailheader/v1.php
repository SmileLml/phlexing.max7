<?php
namespace zin;

class detailHeader extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'back?: string="APP"',
        'backUrl?: string'
    );

    /**
     * @var mixed[]
     */
    protected static $defineBlocks = array(
        'prefix' => array(),
        'title'  => array(),
        'suffix' => array()
    );

    private function backBtn()
    {
        global $lang;
        return backBtn
        (
            set::icon('back'),
            set::type('primary-outline'),
            set::back($this->prop('back')),
            set::url($this->prop('backUrl')),
            $lang->goback
        );
    }

    protected function build()
    {
        $prefix = $this->block('prefix');
        $title  = $this->block('title');
        $suffix = $this->block('suffix');

        if(empty($prefix) && !isAjaxRequest('modal')) $prefix = $this->backBtn();

        return div
        (
            setClass('detail-header flex justify-between mb-3 min-w-0 flex-nowrap', $this->prop('class')),
            div
            (
                setClass('flex flex-auto min-w-0 items-center gap-x-4 flex-nowrap pr-5'),
                $prefix,
                $title
            ),
            $suffix
        );
    }
}
