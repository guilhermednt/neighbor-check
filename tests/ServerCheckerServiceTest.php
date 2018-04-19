<?php

namespace PROCERGS\Monitoring;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PROCERGS\Monitoring\Model\ServerInterface;

class ServerCheckerServiceTest extends TestCase
{
    public function testSuccess()
    {
        $uri = new Uri('https://test/path/to/check');
        $options = [];
        $method = 'POST';

        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(200, [], 'OK'),
        ]);
        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        /** @var ServerInterface|MockObject $server */
        $server = $this->createMock(ServerInterface::class);
        $server->expects($this->once())->method('getHost')->willReturn('server1');

        $service = new ServerCheckerService($client, $uri, $options, $method);

        $this->assertSame('OK', $service->checkServer($server));
        $this->assertCount(1, $container);
        foreach ($container as $transaction) {
            /** @var Request $request */
            $request = $transaction['request'];
            $this->assertSame('POST', $request->getMethod());
            $this->assertNotSame('test', $request->getUri()->getHost());
            $this->assertSame('test', $request->getHeader('Host')[0]);
        }
    }

    public function testFailure()
    {
        $uri = new Uri('https://test/path/to/check');

        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new RequestException('Error', new Request('GET', $uri)),
        ]);
        $handler = HandlerStack::create($mock);
        $handler->push($history);
        $client = new Client(['handler' => $handler]);

        /** @var ServerInterface|MockObject $server */
        $server = $this->createMock(ServerInterface::class);
        $server->expects($this->once())->method('getHost')->willReturn('server1');

        $service = new ServerCheckerService($client, $uri);

        $this->assertSame('Error', $service->checkServer($server));
        $this->assertCount(1, $container);
        foreach ($container as $transaction) {
            /** @var Request $request */
            $request = $transaction['request'];
            $this->assertSame('GET', $request->getMethod());
            $this->assertNotSame('test', $request->getUri()->getHost()[0]);
        }
    }
}
