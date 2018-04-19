<?php

namespace PROCERGS\Monitoring\Model;

use Psr\Http\Message\UriInterface;

interface ServerInterface
{
    /**
     * @param UriInterface $uri
     */
    public function __construct(UriInterface $uri);

    /**
     * @return string
     */
    public function getHost(): string;
}
