<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230104144348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_related (post_a_id INT NOT NULL, post_b_id INT NOT NULL, INDEX IDX_5DBFC7A3F28B3453 (post_a_id), INDEX IDX_5DBFC7A3E03E9BBD (post_b_id), PRIMARY KEY(post_a_id, post_b_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_translations (post_a_id INT NOT NULL, post_b_id INT NOT NULL, INDEX IDX_6D8AA754F28B3453 (post_a_id), INDEX IDX_6D8AA754E03E9BBD (post_b_id), PRIMARY KEY(post_a_id, post_b_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_related ADD CONSTRAINT FK_5DBFC7A3F28B3453 FOREIGN KEY (post_a_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_related ADD CONSTRAINT FK_5DBFC7A3E03E9BBD FOREIGN KEY (post_b_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_translations ADD CONSTRAINT FK_6D8AA754F28B3453 FOREIGN KEY (post_a_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_translations ADD CONSTRAINT FK_6D8AA754E03E9BBD FOREIGN KEY (post_b_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_post DROP FOREIGN KEY FK_93DF0B866FA89B16');
        $this->addSql('ALTER TABLE post_post DROP FOREIGN KEY FK_93DF0B86764DCB99');
        $this->addSql('DROP TABLE post_post');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_post (post_source INT NOT NULL, post_target INT NOT NULL, INDEX IDX_93DF0B86764DCB99 (post_target), INDEX IDX_93DF0B866FA89B16 (post_source), PRIMARY KEY(post_source, post_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE post_post ADD CONSTRAINT FK_93DF0B866FA89B16 FOREIGN KEY (post_source) REFERENCES post (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_post ADD CONSTRAINT FK_93DF0B86764DCB99 FOREIGN KEY (post_target) REFERENCES post (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_related DROP FOREIGN KEY FK_5DBFC7A3F28B3453');
        $this->addSql('ALTER TABLE post_related DROP FOREIGN KEY FK_5DBFC7A3E03E9BBD');
        $this->addSql('ALTER TABLE post_translations DROP FOREIGN KEY FK_6D8AA754F28B3453');
        $this->addSql('ALTER TABLE post_translations DROP FOREIGN KEY FK_6D8AA754E03E9BBD');
        $this->addSql('DROP TABLE post_related');
        $this->addSql('DROP TABLE post_translations');
    }
}
