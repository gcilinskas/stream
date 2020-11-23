<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123183322 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paysera_payment ADD ticket_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paysera_payment ADD CONSTRAINT FK_4A8DD25D700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A8DD25D700047D2 ON paysera_payment (ticket_id)');
        $this->addSql('ALTER TABLE ticket ADD paysera_payment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA322222099 FOREIGN KEY (paysera_payment_id) REFERENCES paysera_payment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97A0ADA322222099 ON ticket (paysera_payment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paysera_payment DROP FOREIGN KEY FK_4A8DD25D700047D2');
        $this->addSql('DROP INDEX UNIQ_4A8DD25D700047D2 ON paysera_payment');
        $this->addSql('ALTER TABLE paysera_payment DROP ticket_id');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA322222099');
        $this->addSql('DROP INDEX UNIQ_97A0ADA322222099 ON ticket');
        $this->addSql('ALTER TABLE ticket DROP paysera_payment_id');
    }
}
