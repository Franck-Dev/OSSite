<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306201923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medias (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, preface VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, modifed_at DATETIME DEFAULT NULL, is_archived TINYINT(1) NOT NULL, fichier VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, type_id INT NOT NULL, created_by_id INT NOT NULL, modifed_by_id INT DEFAULT NULL, INDEX IDX_12D2AF81C54C8C93 (type_id), INDEX IDX_12D2AF81B03A8386 (created_by_id), INDEX IDX_12D2AF81880496E7 (modifed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_medias (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, is_visible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81C54C8C93 FOREIGN KEY (type_id) REFERENCES type_medias (id)');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81880496E7 FOREIGN KEY (modifed_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81C54C8C93');
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81B03A8386');
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81880496E7');
        $this->addSql('DROP TABLE medias');
        $this->addSql('DROP TABLE type_medias');
    }
}
