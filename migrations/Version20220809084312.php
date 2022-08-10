<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220809084312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD thumbnail_id INT DEFAULT NULL, DROP thumbnail');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DFDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES media (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DFDFF2E92 ON post (thumbnail_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DFDFF2E92');
        $this->addSql('DROP INDEX IDX_5A8A6C8DFDFF2E92 ON post');
        $this->addSql('ALTER TABLE post ADD thumbnail LONGTEXT DEFAULT NULL, DROP thumbnail_id');
    }
}
