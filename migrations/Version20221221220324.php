<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221221220324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Removing the Previously Executed Migrations from Production';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DELETE FROM doctrine_migration_versions WHERE version NOT LIKE "%20220926190834" AND version NOT LIKE "%20221221220324"');
    }

    public function down(Schema $schema): void
    {
    }
}
