<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250105215138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medias (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, preface VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, modifed_at DATETIME DEFAULT NULL, is_archived TINYINT(1) NOT NULL, fichier_path VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, fichier VARCHAR(255) DEFAULT NULL, perimetre VARCHAR(255) NOT NULL, type_id INT NOT NULL, created_by_id INT NOT NULL, modifed_by_id INT DEFAULT NULL, visibilite_id INT NOT NULL, INDEX IDX_12D2AF81C54C8C93 (type_id), INDEX IDX_12D2AF81B03A8386 (created_by_id), INDEX IDX_12D2AF81880496E7 (modifed_by_id), INDEX IDX_12D2AF81ADDA9FA7 (visibilite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, reponse VARCHAR(255) DEFAULT NULL, modified_at DATETIME DEFAULT NULL, answered_at DATETIME DEFAULT NULL, is_visible TINYINT(1) NOT NULL, demandeur_id INT DEFAULT NULL, repondeur_id INT DEFAULT NULL, INDEX IDX_8ADC54D595A6EE59 (demandeur_id), INDEX IDX_8ADC54D56C11778F (repondeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81C54C8C93 FOREIGN KEY (type_id) REFERENCES type_medias (id)');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81880496E7 FOREIGN KEY (modifed_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81ADDA9FA7 FOREIGN KEY (visibilite_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D595A6EE59 FOREIGN KEY (demandeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D56C11778F FOREIGN KEY (repondeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64952C5E836 FOREIGN KEY (autorisation_id) REFERENCES statut (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64952C5E836 ON user (autorisation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81C54C8C93');
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81B03A8386');
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81880496E7');
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81ADDA9FA7');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D595A6EE59');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D56C11778F');
        $this->addSql('DROP TABLE medias');
        $this->addSql('DROP TABLE questions');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64952C5E836');
        $this->addSql('DROP INDEX IDX_8D93D64952C5E836 ON user');
    }
}
