<?php

namespace PROCERGS\Test\Monitoring;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PROCERGS\Monitoring\Model\NeighborServerRepositoryInterface;
use PROCERGS\Monitoring\Model\ServerInterface;
use PROCERGS\Monitoring\NeighborCheckService;
use PROCERGS\Monitoring\ServerCheckerService;

class NeighborCheckServiceTest extends TestCase
{
    public function testCheckServers()
    {
        $servers = [
            $this->mockServer('server1'),
            $this->mockServer('server2'),
        ];

        /** @var NeighborServerRepositoryInterface|MockObject $repo */
        $repo = $this->createMock(NeighborServerRepositoryInterface::class);
        $repo->expects($this->once())->method('getNeighbors')->willReturn($servers);

        /** @var ServerCheckerService|MockObject $checker */
        $checker = $this->createMock(ServerCheckerService::class);
        $checker->expects($this->exactly(2))
            ->method('checkServer')->with($this->isInstanceOf(ServerInterface::class))
            ->willReturn('OK');

        $service = new NeighborCheckService($repo, $checker);
        $response = $service->checkNeighbors();

        $this->assertArrayHasKey('server1', $response);
        $this->assertArrayHasKey('server2', $response);
    }

    public function mockServer($host)
    {
        $server = $this->createMock(ServerInterface::class);
        $server->expects($this->once())->method('getHost')->willReturn($host);

        return $server;
    }
}
