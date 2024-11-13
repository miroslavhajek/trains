<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113085943 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE device DROP connected');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE device ADD connected TINYINT(1) NOT NULL');
    }
}
