<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20201212135950
 *
 * database schema
 */
final class Version20201212135950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, movie_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C8F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, movie VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, date DATETIME DEFAULT NULL, preview_url VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1D5EF26F1D5EF26F (movie), UNIQUE INDEX UNIQ_1D5EF26FC53D045F (image), INDEX IDX_1D5EF26F12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paysera_payment (id INT AUTO_INCREMENT NOT NULL, price_id INT DEFAULT NULL, user_id INT DEFAULT NULL, movie_id INT DEFAULT NULL, ticket_id INT DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, update_at DATETIME DEFAULT NULL, token LONGTEXT DEFAULT NULL, INDEX IDX_4A8DD25DD614C7E7 (price_id), INDEX IDX_4A8DD25DA76ED395 (user_id), INDEX IDX_4A8DD25D8F93B6FC (movie_id), UNIQUE INDEX UNIQ_4A8DD25D700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price (id INT AUTO_INCREMENT NOT NULL, movie_id INT DEFAULT NULL, amount INT DEFAULT NULL, created_at DATETIME NOT NULL, active TINYINT(1) NOT NULL, currency VARCHAR(3) NOT NULL, club_price TINYINT(1) NOT NULL, INDEX IDX_CAC822D98F93B6FC (movie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, movie_id INT DEFAULT NULL, paysera_payment_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, seen TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_97A0ADA377153098 (code), INDEX IDX_97A0ADA3A76ED395 (user_id), INDEX IDX_97A0ADA38F93B6FC (movie_id), UNIQUE INDEX UNIQ_97A0ADA322222099 (paysera_payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, role VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, club_request TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE paysera_payment ADD CONSTRAINT FK_4A8DD25DD614C7E7 FOREIGN KEY (price_id) REFERENCES price (id)');
        $this->addSql('ALTER TABLE paysera_payment ADD CONSTRAINT FK_4A8DD25DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE paysera_payment ADD CONSTRAINT FK_4A8DD25D8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE paysera_payment ADD CONSTRAINT FK_4A8DD25D700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D98F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA38F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA322222099 FOREIGN KEY (paysera_payment_id) REFERENCES paysera_payment (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F12469DE2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C8F93B6FC');
        $this->addSql('ALTER TABLE paysera_payment DROP FOREIGN KEY FK_4A8DD25D8F93B6FC');
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D98F93B6FC');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA38F93B6FC');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA322222099');
        $this->addSql('ALTER TABLE paysera_payment DROP FOREIGN KEY FK_4A8DD25DD614C7E7');
        $this->addSql('ALTER TABLE paysera_payment DROP FOREIGN KEY FK_4A8DD25D700047D2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE paysera_payment DROP FOREIGN KEY FK_4A8DD25DA76ED395');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3A76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE paysera_payment');
        $this->addSql('DROP TABLE price');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE user');
    }
}
