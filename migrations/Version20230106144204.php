<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230106144204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_translations (project_a_id INT NOT NULL, project_b_id INT NOT NULL, INDEX IDX_EC103EE424F9B40 (project_a_id), INDEX IDX_EC103EE410FA34AE (project_b_id), PRIMARY KEY(project_a_id, project_b_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_translations ADD CONSTRAINT FK_EC103EE424F9B40 FOREIGN KEY (project_a_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_translations ADD CONSTRAINT FK_EC103EE410FA34AE FOREIGN KEY (project_b_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE post ADD lang VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project ADD lang VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_translations DROP FOREIGN KEY FK_EC103EE424F9B40');
        $this->addSql('ALTER TABLE project_translations DROP FOREIGN KEY FK_EC103EE410FA34AE');
        $this->addSql('DROP TABLE project_translations');
        $this->addSql('ALTER TABLE post DROP lang');
        $this->addSql('ALTER TABLE project DROP lang');
    }
}
