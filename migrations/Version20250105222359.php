<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250105222359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE convention (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, site_id INT DEFAULT NULL, INDEX IDX_8556657EF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE division (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE mandat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE medias (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, preface VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, modifed_at DATETIME DEFAULT NULL, is_archived TINYINT(1) NOT NULL, fichier_path VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, fichier VARCHAR(255) DEFAULT NULL, perimetre VARCHAR(255) NOT NULL, type_id INT NOT NULL, created_by_id INT NOT NULL, modifed_by_id INT DEFAULT NULL, visibilite_id INT NOT NULL, INDEX IDX_12D2AF81C54C8C93 (type_id), INDEX IDX_12D2AF81B03A8386 (created_by_id), INDEX IDX_12D2AF81880496E7 (modifed_by_id), INDEX IDX_12D2AF81ADDA9FA7 (visibilite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, reponse VARCHAR(255) DEFAULT NULL, modified_at DATETIME DEFAULT NULL, answered_at DATETIME DEFAULT NULL, is_visible TINYINT(1) NOT NULL, demandeur_id INT DEFAULT NULL, repondeur_id INT DEFAULT NULL, INDEX IDX_8ADC54D595A6EE59 (demandeur_id), INDEX IDX_8ADC54D56C11778F (repondeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE site_division (site_id INT NOT NULL, division_id INT NOT NULL, INDEX IDX_1C5AF8CEF6BD1646 (site_id), INDEX IDX_1C5AF8CE41859289 (division_id), PRIMARY KEY(site_id, division_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_medias (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, is_visible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, token VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_adhesion DATE DEFAULT NULL, tel INT DEFAULT NULL, division_id INT DEFAULT NULL, autorisation_id INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D64941859289 (division_id), INDEX IDX_8D93D64952C5E836 (autorisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_site (user_id INT NOT NULL, site_id INT NOT NULL, INDEX IDX_13C2452DA76ED395 (user_id), INDEX IDX_13C2452DF6BD1646 (site_id), PRIMARY KEY(user_id, site_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_mandat (user_id INT NOT NULL, mandat_id INT NOT NULL, INDEX IDX_7B01F4E8A76ED395 (user_id), INDEX IDX_7B01F4E8C687DD98 (mandat_id), PRIMARY KEY(user_id, mandat_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE convention ADD CONSTRAINT FK_8556657EF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81C54C8C93 FOREIGN KEY (type_id) REFERENCES type_medias (id)');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81880496E7 FOREIGN KEY (modifed_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF81ADDA9FA7 FOREIGN KEY (visibilite_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D595A6EE59 FOREIGN KEY (demandeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D56C11778F FOREIGN KEY (repondeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE site_division ADD CONSTRAINT FK_1C5AF8CEF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE site_division ADD CONSTRAINT FK_1C5AF8CE41859289 FOREIGN KEY (division_id) REFERENCES division (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64941859289 FOREIGN KEY (division_id) REFERENCES division (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64952C5E836 FOREIGN KEY (autorisation_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE user_site ADD CONSTRAINT FK_13C2452DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_site ADD CONSTRAINT FK_13C2452DF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_mandat ADD CONSTRAINT FK_7B01F4E8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_mandat ADD CONSTRAINT FK_7B01F4E8C687DD98 FOREIGN KEY (mandat_id) REFERENCES mandat (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE convention DROP FOREIGN KEY FK_8556657EF6BD1646');
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81C54C8C93');
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81B03A8386');
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81880496E7');
        $this->addSql('ALTER TABLE medias DROP FOREIGN KEY FK_12D2AF81ADDA9FA7');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D595A6EE59');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D56C11778F');
        $this->addSql('ALTER TABLE site_division DROP FOREIGN KEY FK_1C5AF8CEF6BD1646');
        $this->addSql('ALTER TABLE site_division DROP FOREIGN KEY FK_1C5AF8CE41859289');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64941859289');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64952C5E836');
        $this->addSql('ALTER TABLE user_site DROP FOREIGN KEY FK_13C2452DA76ED395');
        $this->addSql('ALTER TABLE user_site DROP FOREIGN KEY FK_13C2452DF6BD1646');
        $this->addSql('ALTER TABLE user_mandat DROP FOREIGN KEY FK_7B01F4E8A76ED395');
        $this->addSql('ALTER TABLE user_mandat DROP FOREIGN KEY FK_7B01F4E8C687DD98');
        $this->addSql('DROP TABLE convention');
        $this->addSql('DROP TABLE division');
        $this->addSql('DROP TABLE mandat');
        $this->addSql('DROP TABLE medias');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE site_division');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE type_medias');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_site');
        $this->addSql('DROP TABLE user_mandat');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
