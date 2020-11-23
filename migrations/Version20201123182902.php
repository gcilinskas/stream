<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123182902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paysera_payment DROP FOREIGN KEY FK_4A8DD25DC0412EA0');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, movie_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, status INT NOT NULL, seen TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_97A0ADA377153098 (code), INDEX IDX_97A0ADA3A76ED395 (user_id), INDEX IDX_97A0ADA38F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA38F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('DROP TABLE payment_address');
        $this->addSql('DROP INDEX IDX_4A8DD25DC0412EA0 ON paysera_payment');
        $this->addSql('ALTER TABLE paysera_payment DROP payment_address_id');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) DEFAULT NULL, ADD last_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_address (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, country VARCHAR(2) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, first_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_1407616BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE payment_address ADD CONSTRAINT FK_1407616BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('ALTER TABLE paysera_payment ADD payment_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paysera_payment ADD CONSTRAINT FK_4A8DD25DC0412EA0 FOREIGN KEY (payment_address_id) REFERENCES payment_address (id)');
        $this->addSql('CREATE INDEX IDX_4A8DD25DC0412EA0 ON paysera_payment (payment_address_id)');
        $this->addSql('ALTER TABLE user DROP first_name, DROP last_name');
    }
}
