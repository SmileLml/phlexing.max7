<?php
/**
 * The directive class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'zin.class.php';
require_once __DIR__ . DS . 'context.func.php';

use zin\node;

interface iDirective
{
    /**
     * @param \zin\node $node
     * @param string $blockName
     */
    public function apply($node, $blockName);
}

class directive implements iDirective
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var mixed
     */
    public $data;

    /**
     * @var mixed[]|null
     */
    public $options;

    /**
     * @var mixed
     */
    public $parent = null;

    /**
     * Construct a directive object
     * @param  string $type
     * @param  mixed  $data
     * @param  array  $options
     * @access public
     */
    public function __construct($type, $data = null, $options = null)
    {
        $this->type    = $type;
        $this->data    = $data;
        $this->options = $options;

        if(!$options || !isset($options['notRenderInGlobal']) || !$options['notRenderInGlobal'])
        {
            renderInGlobal($this);
        }
    }

    public function __debugInfo()
    {
        return array(
            'type'    => $this->type,
            'data'    => $this->data,
            'options' => $this->options
        );
    }

    /**
     * @param \zin\node $node
     * @param string $blockName
     */
    public function apply($node, $blockName)
    {
        $this->parent = $node;

        $data = $this->data;
        $type = $this->type;

        if($type === 'prop')
        {
            $node->setProp($data);
            return;
        }
        if($type === 'class' || $type === 'style')
        {
            $node->setProp($type, $data);
            return;
        }
        if($type === 'cssVar')
        {
            $node->setProp('--', $data);
            return;
        }
        if($type === 'html')
        {
            $html = new stdClass();
            $html->html = implode("\n", $data);
            $node->addToBlock($blockName, $html);
            return;
        }
        if($type === 'text')
        {
            $node->addToBlock($blockName, $data);
            return;
        }
        if($type === 'block')
        {
            foreach($data as $blockName => $blockChildren)
            {
                $node->addToBlock($blockName, $blockChildren);
            }
        }
    }

    /**
     * @param mixed $item
     */
    public static function is($item)
    {
        return ($item instanceof directive) || $item instanceof iDirective || (is_object($item) && method_exists($item, 'apply'));
    }
}

function directive($type, $data, $options = null)
{
    return new directive($type, $data, $options);
}

/**
 * @param mixed $item
 */
function isDirective($item, $type = null)
{
    return directive::is($item);
}
