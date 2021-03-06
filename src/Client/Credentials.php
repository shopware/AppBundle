<?php declare(strict_types=1);

namespace Shopware\AppBundle\Client;

use Psr\Http\Message\ResponseInterface;

class Credentials
{
    public function __construct(
        private string $tokenType,
        private string $accessToken
    ) {
    }

    public static function fromAuthResponse(ResponseInterface $response): self
    {
        $content = json_decode($response->getBody()->getContents(), true);

        return new self(
            $content['token_type'],
            $content['access_token']
        );
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
}
