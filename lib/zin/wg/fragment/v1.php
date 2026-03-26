<?php
namespace zin;

class fragment extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array
    (
        'rawContent?: bool',
        'hookContent?: bool'
    );

    protected function build()
    {
        $context     = context();
        $css         = array(data('pageCSS'), '/*{{ZIN_PAGE_CSS}}*/');
        $js          = array('/*{{ZIN_PAGE_JS}}*/', data('pageJS'));
        $rawContent  = $this->prop('rawContent', !$context->rawContentCalled);
        $hookContent = $this->prop('hookContent', !$context->hookContentCalled);

        return array
        (
            h::css($css, setClass('zin-page-css')),
            $this->children(),
            $rawContent ? rawContent() : null,
            $hookContent ? hookContent() : null,
            h::js($js, setClass('zin-page-js'))
        );
    }
}
