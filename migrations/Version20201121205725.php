<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201121205725 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paysera_payment CHANGE price price_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paysera_payment ADD CONSTRAINT FK_4A8DD25DD614C7E7 FOREIGN KEY (price_id) REFERENCES price (id)');
        $this->addSql('CREATE INDEX IDX_4A8DD25DD614C7E7 ON paysera_payment (price_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paysera_payment DROP FOREIGN KEY FK_4A8DD25DD614C7E7');
        $this->addSql('DROP INDEX IDX_4A8DD25DD614C7E7 ON paysera_payment');
        $this->addSql('ALTER TABLE paysera_payment CHANGE price_id price INT DEFAULT NULL');
    }
}
