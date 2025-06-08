<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530134124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE right2 (id INT AUTO_INCREMENT NOT NULL, appli_id INT NOT NULL, role VARCHAR(255) NOT NULL, description VARCHAR(2048) NOT NULL, INDEX IDX_BBA0051DC59C41 (appli_id), UNIQUE INDEX UNIQ_BBA0051DC59C4157698A6A (appli_id, role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_right (user_id INT NOT NULL, right_id INT NOT NULL, INDEX IDX_56088E4CA76ED395 (user_id), INDEX IDX_56088E4C54976835 (right_id), PRIMARY KEY(user_id, right_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE right2 ADD CONSTRAINT FK_BBA0051DC59C41 FOREIGN KEY (appli_id) REFERENCES appli (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_right ADD CONSTRAINT FK_56088E4CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_right ADD CONSTRAINT FK_56088E4C54976835 FOREIGN KEY (right_id) REFERENCES right2 (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE right2 DROP FOREIGN KEY FK_BBA0051DC59C41
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_right DROP FOREIGN KEY FK_56088E4CA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_right DROP FOREIGN KEY FK_56088E4C54976835
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE right2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_right
        SQL);
    }
}
