<?php

namespace PROCERGS\Monitoring\Model;

interface NeighborServerRepositoryInterface
{
    /**
     * @return ServerInterface[]
     */
    public function getNeighbors(): array;
}
