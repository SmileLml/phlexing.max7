<?php

namespace Spiral\RoadRunner;

interface WorkerAwareInterface
{
    /**
     * Returns underlying binary worker.
     *
     * @return WorkerInterface
     */
    public function getWorker();
}
