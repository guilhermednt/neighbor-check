<?php

namespace PROCERGS\Test\Monitoring\Model;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PROCERGS\Monitoring\Model\Server;
use Psr\Http\Message\UriInterface;

final class ServerTest extends TestCase
{
    public function testServer()
    {
        /** @var UriInterface|MockObject $uri */
        $uri = $this->createMock(UriInterface::class);
        $uri->expects($this->once())->method('getHost')->willReturn('example.com');

        $server = new Server($uri);
        $this->assertSame('example.com', $server->getHost());
    }
}
