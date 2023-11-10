<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230408152634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dream_book ADD alias VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE interpretation ADD word_id INT NOT NULL, ADD dreambook_id INT DEFAULT NULL, CHANGE caption caption LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE interpretation ADD CONSTRAINT FK_EBDBD117E357438D FOREIGN KEY (word_id) REFERENCES word (id)');
        $this->addSql('ALTER TABLE interpretation ADD CONSTRAINT FK_EBDBD1174A39F96C FOREIGN KEY (dreambook_id) REFERENCES dream_book (id)');
        $this->addSql('CREATE INDEX IDX_EBDBD117E357438D ON interpretation (word_id)');
        $this->addSql('CREATE INDEX IDX_EBDBD1174A39F96C ON interpretation (dreambook_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dream_book DROP alias');
        $this->addSql('ALTER TABLE interpretation DROP FOREIGN KEY FK_EBDBD117E357438D');
        $this->addSql('ALTER TABLE interpretation DROP FOREIGN KEY FK_EBDBD1174A39F96C');
        $this->addSql('DROP INDEX IDX_EBDBD117E357438D ON interpretation');
        $this->addSql('DROP INDEX IDX_EBDBD1174A39F96C ON interpretation');
        $this->addSql('ALTER TABLE interpretation DROP word_id, DROP dreambook_id, CHANGE caption caption VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
