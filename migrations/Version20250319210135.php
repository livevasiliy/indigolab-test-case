<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319210135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sms_code DROP FOREIGN KEY FK_CC38ACCE39DFD528');
        $this->addSql('DROP INDEX UNIQ_CC38ACCE39DFD528 ON sms_code');
        $this->addSql('ALTER TABLE sms_code ADD phone_number VARCHAR(15) NOT NULL, DROP phone_number_id, CHANGE blocked_until blocked_until DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496B01BC5B ON user (phone_number)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sms_code ADD phone_number_id INT DEFAULT NULL, DROP phone_number, CHANGE blocked_until blocked_until TIME NOT NULL');
        $this->addSql('ALTER TABLE sms_code ADD CONSTRAINT FK_CC38ACCE39DFD528 FOREIGN KEY (phone_number_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CC38ACCE39DFD528 ON sms_code (phone_number_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D6496B01BC5B ON user');
    }
}
