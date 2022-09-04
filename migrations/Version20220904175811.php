<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220904175811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE colection (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(255) NOT NULL, maker VARCHAR(255) NOT NULL, origin VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, equipement_id INT NOT NULL, description VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2694D7A5806F0F5C (equipement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, colection_id INT NOT NULL, name VARCHAR(255) NOT NULL, data VARCHAR(255) NOT NULL, INDEX IDX_D8698A76A394B00 (colection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipement (id INT AUTO_INCREMENT NOT NULL, colection_id INT NOT NULL, site_id INT NOT NULL, service VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, used_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', state VARCHAR(255) NOT NULL, INDEX IDX_B8B4C6F3A394B00 (colection_id), INDEX IDX_B8B4C6F3F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, equipement_id INT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', planned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', started_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', finished_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, INDEX IDX_D11814AB806F0F5C (equipement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention_technicien (intervention_id INT NOT NULL, technicien_id INT NOT NULL, INDEX IDX_D4D556418EAE3863 (intervention_id), INDEX IDX_D4D5564113457256 (technicien_id), PRIMARY KEY(intervention_id, technicien_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organe (id INT AUTO_INCREMENT NOT NULL, colection_id INT NOT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_E23012D0A394B00 (colection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE piece (id INT AUTO_INCREMENT NOT NULL, organe_id INT NOT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, amount INT NOT NULL, INDEX IDX_44CA0B23B5E5B09D (organe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5806F0F5C FOREIGN KEY (equipement_id) REFERENCES equipement (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76A394B00 FOREIGN KEY (colection_id) REFERENCES colection (id)');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F3A394B00 FOREIGN KEY (colection_id) REFERENCES colection (id)');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F3F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB806F0F5C FOREIGN KEY (equipement_id) REFERENCES equipement (id)');
        $this->addSql('ALTER TABLE intervention_technicien ADD CONSTRAINT FK_D4D556418EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE intervention_technicien ADD CONSTRAINT FK_D4D5564113457256 FOREIGN KEY (technicien_id) REFERENCES technicien (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organe ADD CONSTRAINT FK_E23012D0A394B00 FOREIGN KEY (colection_id) REFERENCES colection (id)');
        $this->addSql('ALTER TABLE piece ADD CONSTRAINT FK_44CA0B23B5E5B09D FOREIGN KEY (organe_id) REFERENCES organe (id)');
        $this->addSql('ALTER TABLE technicien ADD matricule VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5806F0F5C');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76A394B00');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F3A394B00');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F3F6BD1646');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB806F0F5C');
        $this->addSql('ALTER TABLE intervention_technicien DROP FOREIGN KEY FK_D4D556418EAE3863');
        $this->addSql('ALTER TABLE intervention_technicien DROP FOREIGN KEY FK_D4D5564113457256');
        $this->addSql('ALTER TABLE organe DROP FOREIGN KEY FK_E23012D0A394B00');
        $this->addSql('ALTER TABLE piece DROP FOREIGN KEY FK_44CA0B23B5E5B09D');
        $this->addSql('DROP TABLE colection');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE equipement');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('DROP TABLE intervention_technicien');
        $this->addSql('DROP TABLE organe');
        $this->addSql('DROP TABLE piece');
        $this->addSql('ALTER TABLE technicien DROP matricule');
    }
}
