<?php

namespace PROCERGS\Monitoring;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use PROCERGS\Monitoring\Model\ServerInterface;
use Psr\Http\Message\UriInterface;

class ServerCheckerService
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $method;

    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * @var array
     */
    private $options;

    /**
     * ServerCheckerService constructor.
     * @param ClientInterface $httpClient
     * @param UriInterface $uri
     * @param string $method
     * @param array $options
     */
    public function __construct(
        ClientInterface $httpClient,
        UriInterface $uri,
        array $options = [],
        string $method = 'GET'
    ) {
        $this->httpClient = $httpClient;
        $this->method = $method;
        $this->uri = $uri;
        $this->options = $options;
    }

    public function checkServer(ServerInterface $server)
    {
        try {
            $options = array_merge(['headers' => ['Host' => $this->uri->getHost()]], $this->options);
            $response = $this->httpClient->request($this->method, $this->uri->withHost($server->getHost()), $options);

            return $response->getBody()->__toString();
        } catch (GuzzleException $e) {
            return $e->getMessage();
        }
    }
}
