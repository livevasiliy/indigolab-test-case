<?php

declare(strict_types=1);

namespace App\Service\SmsCode\Storage;

use Doctrine\DBAL\Connection;

class DatabaseSmsCodeStorage implements SmsCodeStorageInterface
{
    /**
     * @var Connection $connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function setCode(string $phoneNumber, string $code, int $ttl = self::DEFAULT_TTL_VALUE): void
    {
        // TTL не используется напрямую в БД, но можно добавить логику удаления старых записей через cron
        $query = '
            INSERT INTO sms_code (phone_number, code, last_sent_at, attempts, blocked_until, created_at)
            VALUES (:phone, :code, NOW(), 0, DATE_ADD(NOW(), INTERVAL :ttl SECOND), NOW())  
            ON DUPLICATE KEY UPDATE code = :code, last_sent_at = NOW()
        ';
        $this->connection->executeQuery($query, ['phone' => $phoneNumber, 'code' => $code, 'ttl' => $ttl]);
    }

    public function getCode(string $phoneNumber): ?string
    {
        $query = 'SELECT code FROM sms_code WHERE phone_number = :phone ORDER BY created_at DESC LIMIT 1';
        $result = $this->connection->fetchAssociative($query, ['phone' => $phoneNumber]);
        return $result['code'] ?? null;
    }
}
