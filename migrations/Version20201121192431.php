<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201121192431 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paysera_payment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, movie_id INT DEFAULT NULL, payment_address_id INT DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, price INT DEFAULT NULL, currency VARCHAR(3) DEFAULT NULL, locale VARCHAR(2) DEFAULT NULL, created_at DATETIME DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_4A8DD25DA76ED395 (user_id), INDEX IDX_4A8DD25D8F93B6FC (movie_id), INDEX IDX_4A8DD25DC0412EA0 (payment_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paysera_payment ADD CONSTRAINT FK_4A8DD25DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE paysera_payment ADD CONSTRAINT FK_4A8DD25D8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE paysera_payment ADD CONSTRAINT FK_4A8DD25DC0412EA0 FOREIGN KEY (payment_address_id) REFERENCES payment_address (id)');
        $this->addSql('ALTER TABLE movie ADD paysera_payments_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F6E80B4E5 FOREIGN KEY (paysera_payments_id) REFERENCES paysera_payment (id)');
        $this->addSql('CREATE INDEX IDX_1D5EF26F6E80B4E5 ON movie (paysera_payments_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F6E80B4E5');
        $this->addSql('DROP TABLE paysera_payment');
        $this->addSql('DROP INDEX IDX_1D5EF26F6E80B4E5 ON movie');
        $this->addSql('ALTER TABLE movie DROP paysera_payments_id');
    }
}
