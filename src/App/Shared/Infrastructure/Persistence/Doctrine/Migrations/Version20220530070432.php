<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530070432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tasks (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', todo_list_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', title VARCHAR(255) NOT NULL COMMENT \'(DC2Type:task_title)\', description VARCHAR(255) NOT NULL COMMENT \'(DC2Type:task_description)\', state INT NOT NULL COMMENT \'(DC2Type:task_state)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tasks');
    }
}
