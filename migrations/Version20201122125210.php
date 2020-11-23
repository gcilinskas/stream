<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201122125210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price CHANGE amount amount NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F6E80B4E5');
        $this->addSql('DROP INDEX IDX_1D5EF26F6E80B4E5 ON movie');
        $this->addSql('ALTER TABLE movie DROP paysera_payments_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price CHANGE amount amount NUMERIC(10, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE movie ADD paysera_payments_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F6E80B4E5 FOREIGN KEY (paysera_payments_id) REFERENCES paysera_payment (id)');
        $this->addSql('CREATE INDEX IDX_1D5EF26F6E80B4E5 ON movie (paysera_payments_id)');
    }
}
