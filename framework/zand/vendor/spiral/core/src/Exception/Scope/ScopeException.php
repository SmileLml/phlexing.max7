<?php

namespace Spiral\Core\Exception\Scope;

use Spiral\Core\Exception\Container\ContainerException;

/**
 * @internal
 */
abstract class ScopeException extends ContainerException
{
    /**
     * @var string|null
     */
    protected $scope;
    /**
     * @param string|null $scope
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($scope, $message = '', $code = 0, $previous = null)
    {
        $this->scope = $scope;
        parent::__construct($message, $code, $previous);
    }
    public function getScope()
    {
        return $this->scope;
    }
}
