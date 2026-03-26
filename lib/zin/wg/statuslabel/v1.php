<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'label' . DS . 'v1.php';

class statusLabel extends label
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array
    (
        'text?:string',
        'status?: string'
    );

    public function build()
    {
        list($text, $status) = $this->prop(array('text', 'status'));
        return span
        (
            setClass($status ? "status-$status" : 'status'),
            set($this->getRestProps()),
            $text,
            $this->children()
        );
    }

    /**
     * @param mixed ...$children
     * @return $this
     * @param string $status
     * @param string $text
     */
    public static function create($status, $text, ...$children)
    {
        $props = array('status' => $status, 'text' => $text);
        return new static(set($props), ...$children);
    }
}
