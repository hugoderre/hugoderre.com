<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220809083933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, thumbnail_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, github_url VARCHAR(255) DEFAULT NULL, website_url VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2FB3D0EEFDFF2E92 (thumbnail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_media (project_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_7979A892166D1F9C (project_id), INDEX IDX_7979A892EA9FDD75 (media_id), PRIMARY KEY(project_id, media_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEFDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE project_media ADD CONSTRAINT FK_7979A892166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_media ADD CONSTRAINT FK_7979A892EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEFDFF2E92');
        $this->addSql('ALTER TABLE project_media DROP FOREIGN KEY FK_7979A892166D1F9C');
        $this->addSql('ALTER TABLE project_media DROP FOREIGN KEY FK_7979A892EA9FDD75');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_media');
    }
}
