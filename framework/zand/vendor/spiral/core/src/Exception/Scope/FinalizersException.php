<?php

namespace Spiral\Core\Exception\Scope;

use Exception;
use Throwable;

final class FinalizersException extends ScopeException
{
    /**
     * @var Throwable[]
     */
    protected $exceptions;
    /**
     * @param Throwable[] $exceptions
     * @param string|null $scope
     */
    public function __construct($scope, $exceptions)
    {
        $this->exceptions = $exceptions;
        $count = \count($exceptions);
        parent::__construct($scope, \sprintf("%s thrown during finalization of %s:\n%s", $count === 1 ? 'An exception has been' : "$count exceptions have been", $scope === null ? 'an unnamed scope' : "the scope `$scope`", \implode("\n\n", \array_map(static function (Exception $e) : string {
            return \sprintf("# %s\n%s", get_class($e), $e->getMessage());
        }, $exceptions))));
    }
    /**
     * @return Throwable[]
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
