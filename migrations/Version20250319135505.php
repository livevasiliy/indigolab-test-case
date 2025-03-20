<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319135505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sms_code (id INT AUTO_INCREMENT NOT NULL, phone_number_id INT DEFAULT NULL, code VARCHAR(4) NOT NULL, attempts INT NOT NULL, last_sent_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', blocked_until TIME NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_CC38ACCE39DFD528 (phone_number_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, phone_number VARCHAR(15) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sms_code ADD CONSTRAINT FK_CC38ACCE39DFD528 FOREIGN KEY (phone_number_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sms_code DROP FOREIGN KEY FK_CC38ACCE39DFD528');
        $this->addSql('DROP TABLE sms_code');
        $this->addSql('DROP TABLE user');
    }
}
