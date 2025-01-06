<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250105161304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81ADDA9FA7 FOREIGN KEY (visibilite_id) REFERENCES statut (id)');
        $this->addSql('CREATE INDEX IDX_12D2AF81ADDA9FA7 ON medias (visibilite_id)');
        $this->addSql('ALTER TABLE user ADD autorisation_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64952C5E836 FOREIGN KEY (autorisation_id) REFERENCES statut (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64952C5E836 ON user (autorisation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81ADDA9FA7');
        $this->addSql('DROP INDEX IDX_12D2AF81ADDA9FA7 ON medias');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64952C5E836');
        $this->addSql('DROP INDEX IDX_8D93D64952C5E836 ON user');
        $this->addSql('ALTER TABLE user DROP autorisation_id');
    }
}
