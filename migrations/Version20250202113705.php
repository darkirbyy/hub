<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202113705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appli ADD image_meta_name VARCHAR(255) DEFAULT NULL, ADD image_meta_original_name VARCHAR(255) DEFAULT NULL, ADD image_meta_mime_type VARCHAR(255) DEFAULT NULL, DROP image_name, DROP image_mime_type, DROP image_original_name, CHANGE image_size image_meta_size INT DEFAULT NULL, CHANGE image_dimensions image_meta_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appli ADD image_name VARCHAR(255) DEFAULT NULL, ADD image_mime_type VARCHAR(255) DEFAULT NULL, ADD image_original_name VARCHAR(255) DEFAULT NULL, DROP image_meta_name, DROP image_meta_original_name, DROP image_meta_mime_type, CHANGE image_meta_size image_size INT DEFAULT NULL, CHANGE image_meta_dimensions image_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\'');
    }
}
