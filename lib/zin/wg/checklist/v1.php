<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';

class checkList extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'primary: bool=true',
        'className?: string',
        'type: string="checkbox"',
        'name?: string',
        'value?: string|array',
        'items?: array',
        'inline?: bool',
        'disabled?: bool',
        'title?: string'
    );

    public function getValueList()
    {
        $value = $this->prop('value');
        if(is_null($value)) return array();

        if($this->prop('type') === 'checkbox') return is_array($value) ? $value : explode(',', $value);
        return array($value);
    }

    public function onBuildItem($item)
    {
        if($item instanceof item) $item = $item->props->toJSON();

        if(!isset($item['checked']))
        {
            $value     = isset($item['value']) ? $item['value'] : '';
            $valueList = $this->getValueList();

            $item['checked'] = in_array($value, $valueList);
            $item['disabled'] = $this->prop('disabled');
        }

        $props = $this->props->pick(array('primary', 'type', 'name', 'disabled'));
        if(!empty($props['name']) && isset($item['value'])) $props['id'] = str_replace('[]', '', $props['name']) . (is_null($item['value']) ? '' : $item['value']);
        return $this->buildItem(array_merge($props, $item));
    }

    /**
     * @param mixed[] $props
     */
    public function buildItem($props)
    {
        return new checkbox(set($props));
    }

    protected function build()
    {
        list($items, $inline, $disabled, $className, $title) = $this->prop(array('items', 'inline', 'disabled', 'className', 'title'));

        if(!empty($items))
        {
            $valueList = $this->getValueList();
            foreach($items as $key => $item)
            {
                if(!is_array($item))         $item = array('text' => $item, 'value' => $key);
                if(!isset($item['checked'])) $item['checked'] = in_array($item['value'], $valueList);
                if(!empty($item[$title])) $item['title'] = $item[$title];
                $items[$key] = $this->onBuildItem($item);
            }
        }

        return div
        (
            setClass($inline ? 'check-list-inline' : 'check-list', $className, $disabled ? 'disabled' : ''),
            set($this->getRestProps()),
            $items,
            $this->children()
        );
    }
}
