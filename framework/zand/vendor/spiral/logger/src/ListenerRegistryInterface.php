<?php

namespace Spiral\Logger;

interface ListenerRegistryInterface
{
    /**
     * Add new even listener.
     * @param callable $listener
     */
    public function addListener($listener);

    /**
     * Add LogEvent listener.
     * @param callable $listener
     */
    public function removeListener($listener);

    /**
     * @return callable[]
     */
    public function getListeners();
}
