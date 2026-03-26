<?php
namespace zin;

class idLabel extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array
    (
        'id?: int|string'
    );

    public function onAddChild($child)
    {
        if((is_string($child) || is_int($child)) && !$this->props->has('id'))
        {
            $this->props->set('id', $child);
            return false;
        }
    }

    protected function build()
    {
        $id = $this->prop('id');
        return span
        (
            setClass('label label-id gray-300-outline size-sm rounded-full flex-none'),
            set($this->getRestProps()),
            $id,
            $this->children()
        );
    }

    /**
     * @param string|int|mixed[] $idOrProps
     * @param mixed ...$children
     * @return $this
     * @param mixed[]|null $props
     */
    public static function create($idOrProps, $props = null, ...$children)
    {
        $props = $props ? $props : array();
        if(is_array($idOrProps))
        {
            $props = array_merge($idOrProps, $props);
        }
        else
        {
            $props['id'] = $idOrProps;
        }
        return new static(set($props), ...$children);
    }
}
