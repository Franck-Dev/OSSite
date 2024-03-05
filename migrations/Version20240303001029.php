<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303001029 extends AbstractMigration
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
        $this->addSql('CREATE TABLE mandat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE site_division (site_id INT NOT NULL, division_id INT NOT NULL, INDEX IDX_1C5AF8CEF6BD1646 (site_id), INDEX IDX_1C5AF8CE41859289 (division_id), PRIMARY KEY(site_id, division_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, division_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D64941859289 (division_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_site (user_id INT NOT NULL, site_id INT NOT NULL, INDEX IDX_13C2452DA76ED395 (user_id), INDEX IDX_13C2452DF6BD1646 (site_id), PRIMARY KEY(user_id, site_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_mandat (user_id INT NOT NULL, mandat_id INT NOT NULL, INDEX IDX_7B01F4E8A76ED395 (user_id), INDEX IDX_7B01F4E8C687DD98 (mandat_id), PRIMARY KEY(user_id, mandat_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE convention ADD CONSTRAINT FK_8556657EF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE site_division ADD CONSTRAINT FK_1C5AF8CEF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE site_division ADD CONSTRAINT FK_1C5AF8CE41859289 FOREIGN KEY (division_id) REFERENCES division (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64941859289 FOREIGN KEY (division_id) REFERENCES division (id)');
        $this->addSql('ALTER TABLE user_site ADD CONSTRAINT FK_13C2452DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_site ADD CONSTRAINT FK_13C2452DF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_mandat ADD CONSTRAINT FK_7B01F4E8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_mandat ADD CONSTRAINT FK_7B01F4E8C687DD98 FOREIGN KEY (mandat_id) REFERENCES mandat (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE convention DROP FOREIGN KEY FK_8556657EF6BD1646');
        $this->addSql('ALTER TABLE site_division DROP FOREIGN KEY FK_1C5AF8CEF6BD1646');
        $this->addSql('ALTER TABLE site_division DROP FOREIGN KEY FK_1C5AF8CE41859289');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64941859289');
        $this->addSql('ALTER TABLE user_site DROP FOREIGN KEY FK_13C2452DA76ED395');
        $this->addSql('ALTER TABLE user_site DROP FOREIGN KEY FK_13C2452DF6BD1646');
        $this->addSql('ALTER TABLE user_mandat DROP FOREIGN KEY FK_7B01F4E8A76ED395');
        $this->addSql('ALTER TABLE user_mandat DROP FOREIGN KEY FK_7B01F4E8C687DD98');
        $this->addSql('DROP TABLE convention');
        $this->addSql('DROP TABLE division');
        $this->addSql('DROP TABLE mandat');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE site_division');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_site');
        $this->addSql('DROP TABLE user_mandat');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
