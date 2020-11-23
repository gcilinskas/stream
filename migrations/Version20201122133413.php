<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201122133413 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D98F93B6FC');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D98F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D98F93B6FC');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D98F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
    }
}
