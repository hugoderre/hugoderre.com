<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220926185345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD image_seo_id INT DEFAULT NULL, ADD description_seo LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D38FE834E FOREIGN KEY (image_seo_id) REFERENCES media (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D38FE834E ON post (image_seo_id)');
        $this->addSql('ALTER TABLE project ADD image_seo_id INT DEFAULT NULL, ADD description_seo LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE38FE834E FOREIGN KEY (image_seo_id) REFERENCES media (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE38FE834E ON project (image_seo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE38FE834E');
        $this->addSql('DROP INDEX IDX_2FB3D0EE38FE834E ON project');
        $this->addSql('ALTER TABLE project DROP image_seo_id, DROP description_seo');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D38FE834E');
        $this->addSql('DROP INDEX IDX_5A8A6C8D38FE834E ON post');
        $this->addSql('ALTER TABLE post DROP image_seo_id, DROP description_seo');
    }
}
