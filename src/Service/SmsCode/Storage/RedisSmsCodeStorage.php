<?php

declare(strict_types=1);

namespace App\Service\SmsCode\Storage;

use Predis\Client;

class RedisSmsCodeStorage implements SmsCodeStorageInterface
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setCode(string $phoneNumber, string $code, int $ttl = self::DEFAULT_TTL_VALUE): void
    {
        $this->client->setex("sms_code:$phoneNumber", $ttl, $code);
    }

    public function getCode(string $phoneNumber): ?string
    {
        return $this->client->get("sms_code:$phoneNumber");
    }
}
