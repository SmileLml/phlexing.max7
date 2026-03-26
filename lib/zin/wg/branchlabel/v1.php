<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'label' . DS . 'v1.php';

class branchLabel extends label
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array
    (
        'text?:string',
        'branch?: int'
    );

    public function build()
    {
        list($text, $branch) = $this->prop(array('text', 'branch'));
        return span
        (
            setClass(empty($branch) ? 'text-primary secondary-outline' : 'gray-300-outline'),
            setClass('label size-sm rounded-full flex-none text-clip mx-1'),
            setStyle('max-width', '60px'),
            set($this->getRestProps()),
            $text,
            $this->children()
        );
    }

    /**
     * @param mixed ...$children
     * @return $this
     * @param int $branch
     * @param string $text
     */
    public static function create($branch, $text, ...$children)
    {
        $props = array('branch' => $branch, 'text' => $text);
        return new static(set($props), ...$children);
    }
}
