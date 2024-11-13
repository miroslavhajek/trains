<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241112195941 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE device_location DROP state');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE device_location ADD state VARCHAR(64) NOT NULL');
    }
}
