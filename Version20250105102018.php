<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250105102018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE medias ADD perimetre VARCHAR(255) NOT NULL, ADD visibilite_id INT NOT NULL');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81ADDA9FA7 FOREIGN KEY (visibilite_id) REFERENCES statut (id)');
        $this->addSql('CREATE INDEX IDX_12D2AF81ADDA9FA7 ON medias (visibilite_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE statut');
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81ADDA9FA7');
        $this->addSql('DROP INDEX IDX_12D2AF81ADDA9FA7 ON medias');
        $this->addSql('ALTER TABLE medias DROP perimetre, DROP visibilite_id');
    }
}
