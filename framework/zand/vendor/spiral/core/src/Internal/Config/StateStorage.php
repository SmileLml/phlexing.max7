<?php

namespace Spiral\Core\Internal\Config;

use Spiral\Core\Internal\State;

class StateStorage
{
    /** @var array<string, State> */
    private $states = [];

    /**
     * Get bindings for the given scope.
     * @param string $scope
     */
    public function getState($scope)
    {
        return $this->states[$scope] = $this->states[$scope] ?? new State();
    }
}
