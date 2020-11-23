<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123190416 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paysera_payment DROP FOREIGN KEY FK_4A8DD25D8F93B6FC');
        $this->addSql('ALTER TABLE paysera_payment ADD CONSTRAINT FK_4A8DD25D8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE price CHANGE amount amount INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paysera_payment DROP FOREIGN KEY FK_4A8DD25D8F93B6FC');
        $this->addSql('ALTER TABLE paysera_payment ADD CONSTRAINT FK_4A8DD25D8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE price CHANGE amount amount NUMERIC(10, 2) DEFAULT NULL');
    }
}
