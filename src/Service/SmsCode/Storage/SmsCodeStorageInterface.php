<?php

declare(strict_types=1);


namespace App\Service\SmsCode\Storage;

interface SmsCodeStorageInterface
{
    public const DEFAULT_TTL_VALUE = 60;

    /**
     * Сохранить код подтверждения для номера телефона.
     */
    public function setCode(string $phoneNumber, string $code, int $ttl = self::DEFAULT_TTL_VALUE): void;

    /**
     * Получить код подтверждения по номеру телефона.
     *
     * @param string $phoneNumber
     * @return string|null
     */
    public function getCode(string $phoneNumber): ?string;
}
