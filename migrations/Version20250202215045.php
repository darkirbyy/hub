<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202215045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appli (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, link_text VARCHAR(255) NOT NULL, image_updated_at DATETIME DEFAULT NULL, image_meta_name VARCHAR(255) DEFAULT NULL, image_meta_original_name VARCHAR(255) DEFAULT NULL, image_meta_mime_type VARCHAR(255) DEFAULT NULL, image_meta_size INT DEFAULT NULL, image_meta_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_A0989C7B12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, icon_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_64C19C154B9D732 (icon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE icon (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, fa_class VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appli ADD CONSTRAINT FK_A0989C7B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C154B9D732 FOREIGN KEY (icon_id) REFERENCES icon (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appli DROP FOREIGN KEY FK_A0989C7B12469DE2');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C154B9D732');
        $this->addSql('DROP TABLE appli');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE icon');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
