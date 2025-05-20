<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520184933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0989C7B5E237E06 ON appli (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0989C7BB548B0F ON appli (path)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0989C7B96901F5412469DE2 ON appli (number, category_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C196901F54 ON category (number)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_659429DB88D665A4 ON icon (fa_class)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4CFF929B9096453 ON meta_role (key_meta_role)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57698A6A1DC59C41D16E51A3 ON role (appli_id, key_role)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2EF83F9C96901F548CDE5729 ON shortcut (number, type)');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_identifier_username TO UNIQ_8D93D649F85E0677');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_64C19C196901F54 ON category');
        $this->addSql('DROP INDEX UNIQ_A0989C7B5E237E06 ON appli');
        $this->addSql('DROP INDEX UNIQ_A0989C7BB548B0F ON appli');
        $this->addSql('DROP INDEX UNIQ_A0989C7B96901F5412469DE2 ON appli');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649f85e0677 TO UNIQ_IDENTIFIER_USERNAME');
        $this->addSql('DROP INDEX UNIQ_C4CFF929B9096453 ON meta_role');
        $this->addSql('DROP INDEX UNIQ_659429DB88D665A4 ON icon');
        $this->addSql('DROP INDEX UNIQ_57698A6A1DC59C41D16E51A3 ON role');
        $this->addSql('DROP INDEX UNIQ_2EF83F9C96901F548CDE5729 ON shortcut');
    }
}
