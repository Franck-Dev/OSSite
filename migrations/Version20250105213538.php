<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250105213538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, reponse VARCHAR(255) DEFAULT NULL, modified_at DATETIME DEFAULT NULL, answered_at DATETIME DEFAULT NULL, is_visible TINYINT(1) NOT NULL, demandeur_id INT DEFAULT NULL, repondeur_id INT DEFAULT NULL, INDEX IDX_8ADC54D595A6EE59 (demandeur_id), INDEX IDX_8ADC54D56C11778F (repondeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D595A6EE59 FOREIGN KEY (demandeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D56C11778F FOREIGN KEY (repondeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64952C5E836 FOREIGN KEY (autorisation_id) REFERENCES statut (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64952C5E836 ON user (autorisation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D595A6EE59');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D56C11778F');
        $this->addSql('DROP TABLE questions');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64952C5E836');
        $this->addSql('DROP INDEX IDX_8D93D64952C5E836 ON user');
    }
}
