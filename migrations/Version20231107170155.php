<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107170155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE interpretation DROP FOREIGN KEY FK_EBDBD1174A39F96C');
        $this->addSql('CREATE TABLE element (id INT AUTO_INCREMENT NOT NULL, type_element_id INT DEFAULT NULL, caption VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, alias VARCHAR(255) NOT NULL, INDEX IDX_41405E3921CFC01 (type_element_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE element_rule (element_id INT NOT NULL, rule_id INT NOT NULL, INDEX IDX_5E68D351F1F2A24 (element_id), INDEX IDX_5E68D35744E0351 (rule_id), PRIMARY KEY(element_id, rule_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_element (id INT AUTO_INCREMENT NOT NULL, caption VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, alias VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E3921CFC01 FOREIGN KEY (type_element_id) REFERENCES type_element (id)');
        $this->addSql('ALTER TABLE element_rule ADD CONSTRAINT FK_5E68D351F1F2A24 FOREIGN KEY (element_id) REFERENCES element (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE element_rule ADD CONSTRAINT FK_5E68D35744E0351 FOREIGN KEY (rule_id) REFERENCES rule (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE dream_book');
        $this->addSql('DROP TABLE interpretation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE element_rule DROP FOREIGN KEY FK_5E68D351F1F2A24');
        $this->addSql('ALTER TABLE element DROP FOREIGN KEY FK_41405E3921CFC01');
        $this->addSql('CREATE TABLE dream_book (id INT AUTO_INCREMENT NOT NULL, caption VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, alias VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE interpretation (id INT AUTO_INCREMENT NOT NULL, word_id INT NOT NULL, dreambook_id INT DEFAULT NULL, caption LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_EBDBD117E357438D (word_id), INDEX IDX_EBDBD1174A39F96C (dreambook_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE interpretation ADD CONSTRAINT FK_EBDBD1174A39F96C FOREIGN KEY (dreambook_id) REFERENCES dream_book (id)');
        $this->addSql('ALTER TABLE interpretation ADD CONSTRAINT FK_EBDBD117E357438D FOREIGN KEY (word_id) REFERENCES word (id)');
        $this->addSql('DROP TABLE element');
        $this->addSql('DROP TABLE element_rule');
        $this->addSql('DROP TABLE type_element');
    }
}
