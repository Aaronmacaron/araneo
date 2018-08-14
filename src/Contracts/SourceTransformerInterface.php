<?php

namespace Araneo\Contracts;

use GuzzleHttp\Psr7\Response;

interface SourceTransformerInterface
{
    public function transform(Response $payload): array;
}
