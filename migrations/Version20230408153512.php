<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230408153512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE letter (id INT AUTO_INCREMENT NOT NULL, caption VARCHAR(1) NOT NULL, alias VARCHAR(4) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE word ADD letter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE word ADD CONSTRAINT FK_C3F175114525FF26 FOREIGN KEY (letter_id) REFERENCES letter (id)');
        $this->addSql('CREATE INDEX IDX_C3F175114525FF26 ON word (letter_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE word DROP FOREIGN KEY FK_C3F175114525FF26');
        $this->addSql('DROP TABLE letter');
        $this->addSql('DROP INDEX IDX_C3F175114525FF26 ON word');
        $this->addSql('ALTER TABLE word DROP letter_id');
    }
}
