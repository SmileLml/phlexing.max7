<?php
/**
 * The js context class file of zin lib.
 *
 * @copyright   Copyright 2024 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'js.class.php';

/**
 * Class for generating js code with context.
 */
class jsContext extends js
{
    /**
     * @var string|null
     */
    protected $contextName;

    /**
     * @param null|string|bool $name
     * @param string|mixed[]|\zin\js|null ...$codes
     * @param mixed $value
     */
    public function __construct($value, $name = null, ...$codes)
    {
        parent::__construct(...$codes);

        if($name === false)
        {
            $this->contextName = $value;
        }
        else
        {
            if(!is_string($name)) $name = '__C' . (static::$tempIndex++);
            $this->contextName = $name;
            $this->const($name, jsRaw($value));
        }
    }

    public function __call($name, $arguments)
    {
        if(str_ends_with($name, 'If'))
        {
            return parent::__call($name, $arguments);
        }

        return $this->call($name, ...$arguments);
    }

    /**
     * @param mixed ...$args
     * @return $this
     * @param string $func
     */
    public function call($func, ...$args)
    {
        return parent::call($this->contextName . '.' . $func, ...$args);
    }

    /**
     * @param mixed ...$args
     */
    public function callSelf(...$args)
    {
        return parent::call($this->contextName, ...$args);
    }

    /**
     * @param string|mixed[]|\zin\js|null ...$codes
     * @return $this
     */
    public function do(...$codes)
    {
        return $this->with($this->contextName, ...$codes);
    }

    /**
     * @var int
     */
    public static $tempIndex = 0;
}

/**
 * @param string|mixed[]|null $codes
 * @param mixed $value
 */
function jsContext($value, $name = null, $codes = null)
{
    return new jsContext($value, $name, $codes);
}
