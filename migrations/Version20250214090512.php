<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214090512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD meta_role_id INT DEFAULT NULL, ADD meta_admin TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499F783D4E FOREIGN KEY (meta_role_id) REFERENCES meta_role (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6499F783D4E ON user (meta_role_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499F783D4E');
        $this->addSql('DROP INDEX IDX_8D93D6499F783D4E ON user');
        $this->addSql('ALTER TABLE user DROP meta_role_id, DROP meta_admin');
    }
}
