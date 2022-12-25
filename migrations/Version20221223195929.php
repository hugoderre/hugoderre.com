<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221223195929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_post (post_source INT NOT NULL, post_target INT NOT NULL, INDEX IDX_93DF0B866FA89B16 (post_source), INDEX IDX_93DF0B86764DCB99 (post_target), PRIMARY KEY(post_source, post_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_post ADD CONSTRAINT FK_93DF0B866FA89B16 FOREIGN KEY (post_source) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_post ADD CONSTRAINT FK_93DF0B86764DCB99 FOREIGN KEY (post_target) REFERENCES post (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_post DROP FOREIGN KEY FK_93DF0B866FA89B16');
        $this->addSql('ALTER TABLE post_post DROP FOREIGN KEY FK_93DF0B86764DCB99');
        $this->addSql('DROP TABLE post_post');
    }
}
