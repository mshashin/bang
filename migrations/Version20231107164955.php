<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107164955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE code (id INT AUTO_INCREMENT NOT NULL, code LONGTEXT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dream_calendar (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, number INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, probability INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE letter (id INT AUTO_INCREMENT NOT NULL, caption VARCHAR(1) NOT NULL, alias VARCHAR(4) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rule (id INT AUTO_INCREMENT NOT NULL, caption VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE word (id INT AUTO_INCREMENT NOT NULL, letter_id INT DEFAULT NULL, caption VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, views INT NOT NULL, image VARCHAR(255) DEFAULT NULL, plural TINYINT(1) DEFAULT NULL, cases VARCHAR(4) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_C3F175114525FF26 (letter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE word ADD CONSTRAINT FK_C3F175114525FF26 FOREIGN KEY (letter_id) REFERENCES letter (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE word DROP FOREIGN KEY FK_C3F175114525FF26');
        $this->addSql('DROP TABLE code');
        $this->addSql('DROP TABLE dream_calendar');
        $this->addSql('DROP TABLE letter');
        $this->addSql('DROP TABLE rule');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE word');
    }
}
