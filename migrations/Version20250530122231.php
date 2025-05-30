<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530122231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499F783D4E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meta_role_role DROP FOREIGN KEY FK_8962D1E9F783D4E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meta_role_role DROP FOREIGN KEY FK_8962D1ED60322AC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE meta_role_role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE meta_role
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D6499F783D4E ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP meta_role_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE meta_role_role (meta_role_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_8962D1E9F783D4E (meta_role_id), INDEX IDX_8962D1ED60322AC (role_id), PRIMARY KEY(meta_role_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE meta_role (id INT AUTO_INCREMENT NOT NULL, key_meta_role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(2048) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_C4CFF929B9096453 (key_meta_role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meta_role_role ADD CONSTRAINT FK_8962D1E9F783D4E FOREIGN KEY (meta_role_id) REFERENCES meta_role (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE meta_role_role ADD CONSTRAINT FK_8962D1ED60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD meta_role_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D6499F783D4E FOREIGN KEY (meta_role_id) REFERENCES meta_role (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D6499F783D4E ON user (meta_role_id)
        SQL);
    }
}
