<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214075701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meta_role (id INT AUTO_INCREMENT NOT NULL, key_meta_role VARCHAR(255) NOT NULL, description VARCHAR(2048) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meta_role_role (meta_role_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_8962D1E9F783D4E (meta_role_id), INDEX IDX_8962D1ED60322AC (role_id), PRIMARY KEY(meta_role_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meta_role_role ADD CONSTRAINT FK_8962D1E9F783D4E FOREIGN KEY (meta_role_id) REFERENCES meta_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meta_role_role ADD CONSTRAINT FK_8962D1ED60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meta_role_role DROP FOREIGN KEY FK_8962D1E9F783D4E');
        $this->addSql('ALTER TABLE meta_role_role DROP FOREIGN KEY FK_8962D1ED60322AC');
        $this->addSql('DROP TABLE meta_role');
        $this->addSql('DROP TABLE meta_role_role');
    }
}
