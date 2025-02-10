<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203200138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE external_link (id INT AUTO_INCREMENT NOT NULL, icon_id INT NOT NULL, appli_id INT NOT NULL, text VARCHAR(255) DEFAULT NULL, url VARCHAR(2048) NOT NULL, INDEX IDX_A3B3F9DD54B9D732 (icon_id), INDEX IDX_A3B3F9DD1DC59C41 (appli_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE external_link ADD CONSTRAINT FK_A3B3F9DD54B9D732 FOREIGN KEY (icon_id) REFERENCES icon (id)');
        $this->addSql('ALTER TABLE external_link ADD CONSTRAINT FK_A3B3F9DD1DC59C41 FOREIGN KEY (appli_id) REFERENCES appli (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE external_link DROP FOREIGN KEY FK_A3B3F9DD54B9D732');
        $this->addSql('ALTER TABLE external_link DROP FOREIGN KEY FK_A3B3F9DD1DC59C41');
        $this->addSql('DROP TABLE external_link');
    }
}
