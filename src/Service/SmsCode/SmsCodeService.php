<?php

declare(strict_types=1);

namespace App\Service\SmsCode;

use App\Exception\TooManyAttemptsException;
use App\Repository\SmsCodeRepository;
use App\Service\SmsCode\Storage\SmsCodeStorageInterface;

class SmsCodeService
{
    /**
     * @var SmsCodeStorageInterface $storage
     */
    private $storage;

    /**
     * @var int $ttl
     */
    private $ttl;

    /**
     * @var SmsCodeRepository
     */
    private $smsCodeRepository;

    public function __construct(
        SmsCodeStorageInterface $storage,
        SmsCodeRepository $smsCodeRepository,
        int $ttl
    )
    {
        $this->storage = $storage;
        $this->ttl = $ttl;
        $this->smsCodeRepository = $smsCodeRepository;
    }

    public function requestCode(string $phoneNumber): string
    {
        $smsCode = $this->smsCodeRepository->findLatestByPhoneNumber($phoneNumber);
        $smsCodeFromStorage = $this->storage->getCode($phoneNumber);

        if ($smsCode && $smsCode->isBlocked()) {
            throw new TooManyAttemptsException();
        }

        if ($smsCode && !$smsCode->canResend()) {
            return $smsCode->getCode();
        }

        if (!$smsCode && $smsCodeFromStorage) {
            return $smsCodeFromStorage;
        }

        $newSmsCode = $this->generateRandomCode();
        $this->storage->setCode($phoneNumber, $newSmsCode);

        if ($smsCode) {
            $this->smsCodeRepository->updateCode($smsCode, $newSmsCode);
        } else {
            $this->smsCodeRepository->createNewCode($phoneNumber, $newSmsCode);
        }

        return $newSmsCode;
    }

    private function generateRandomCode(): string
    {
        return str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
