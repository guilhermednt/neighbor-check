<?php

namespace PROCERGS\Monitoring\Model;

use Psr\Http\Message\UriInterface;

final class Server implements ServerInterface
{
    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * @param UriInterface $uri
     */
    public function __construct(UriInterface $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->uri->getHost();
    }
}
