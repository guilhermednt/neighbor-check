<?php

namespace PROCERGS\Monitoring;

use PROCERGS\Monitoring\Model\NeighborServerRepositoryInterface;

class NeighborCheckService
{
    /**
     * @var NeighborServerRepositoryInterface
     */
    private $neighborServerRepository;

    /**
     * @var ServerCheckerService
     */
    private $serverChecker;

    /**
     * NeighborCheckService constructor.
     * @param NeighborServerRepositoryInterface $neighborServerRepository
     * @param ServerCheckerService $serverChecker
     */
    public function __construct(
        NeighborServerRepositoryInterface $neighborServerRepository,
        ServerCheckerService $serverChecker
    ) {
        $this->neighborServerRepository = $neighborServerRepository;
        $this->serverChecker = $serverChecker;
    }

    /**
     * @return array
     */
    public function checkNeighbors()
    {
        $servers = $this->neighborServerRepository->getNeighbors();
        $result = [];

        foreach ($servers as $server) {
            $result[$server->getHost()] = $this->serverChecker->checkServer($server);
        }

        return $result;
    }
}
