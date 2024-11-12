<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241112114204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE device (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, connected TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_92FB68E5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE device_location (id INT AUTO_INCREMENT NOT NULL, state VARCHAR(64) NOT NULL, lat VARCHAR(32) NOT NULL, lon VARCHAR(32) NOT NULL, created_at DATETIME NOT NULL, remote_created_at DATETIME NOT NULL, device_id VARCHAR(36) NOT NULL, INDEX IDX_D0AAD0EB94A4C7D4 (device_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE remote_hub (id INT AUTO_INCREMENT NOT NULL, remote_id BINARY(16) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE remote_location (id INT AUTO_INCREMENT NOT NULL, lat VARCHAR(32) NOT NULL, lon VARCHAR(32) NOT NULL, created_at DATETIME NOT NULL, state VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE device_location ADD CONSTRAINT FK_D0AAD0EB94A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE device_location DROP FOREIGN KEY FK_D0AAD0EB94A4C7D4');
        $this->addSql('DROP TABLE device');
        $this->addSql('DROP TABLE device_location');
        $this->addSql('DROP TABLE remote_hub');
        $this->addSql('DROP TABLE remote_location');
        $this->addSql('DROP TABLE `user`');
    }
}
