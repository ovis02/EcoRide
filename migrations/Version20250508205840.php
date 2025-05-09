<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250508205840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrateur (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, cible_id INT NOT NULL, gere_par_id INT DEFAULT NULL, note VARCHAR(255) NOT NULL, commentaire LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, valide TINYINT(1) NOT NULL, INDEX IDX_8F91ABF060BB6FE6 (auteur_id), INDEX IDX_8F91ABF0A96E5E09 (cible_id), INDEX IDX_8F91ABF0412306B8 (gere_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE covoiturage (id INT AUTO_INCREMENT NOT NULL, chauffeur_id INT NOT NULL, vehicule_id INT NOT NULL, depart VARCHAR(255) NOT NULL, arrivee VARCHAR(255) NOT NULL, date_depart DATETIME NOT NULL, date_arrivee DATETIME NOT NULL, nb_places_dispo INT NOT NULL, prix DOUBLE PRECISION NOT NULL, est_ecologique TINYINT(1) NOT NULL, INDEX IDX_28C79E8985C0B3BE (chauffeur_id), INDEX IDX_28C79E894A4A3511 (vehicule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE covoiturage_user (covoiturage_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F862CC4962671590 (covoiturage_id), INDEX IDX_F862CC49A76ED395 (user_id), PRIMARY KEY(covoiturage_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, cree_par_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F804D3B9E7927C74 (email), INDEX IDX_F804D3B9FC29C013 (cree_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_contact (id INT AUTO_INCREMENT NOT NULL, traite_par_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, date_envoi DATETIME NOT NULL, INDEX IDX_DCEADC34167FABE8 (traite_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preference (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, fumeur TINYINT(1) NOT NULL, animaux TINYINT(1) NOT NULL, autres_preferences VARCHAR(255) DEFAULT NULL, INDEX IDX_5D69B053FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, credits INT NOT NULL, role VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicule (id INT AUTO_INCREMENT NOT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, energie VARCHAR(255) NOT NULL, plaque_immatriculation VARCHAR(255) NOT NULL, date_premiere_immatriculation DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF060BB6FE6 FOREIGN KEY (auteur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0A96E5E09 FOREIGN KEY (cible_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0412306B8 FOREIGN KEY (gere_par_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E8985C0B3BE FOREIGN KEY (chauffeur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E894A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicule (id)');
        $this->addSql('ALTER TABLE covoiturage_user ADD CONSTRAINT FK_F862CC4962671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covoiturage_user ADD CONSTRAINT FK_F862CC49A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9FC29C013 FOREIGN KEY (cree_par_id) REFERENCES administrateur (id)');
        $this->addSql('ALTER TABLE message_contact ADD CONSTRAINT FK_DCEADC34167FABE8 FOREIGN KEY (traite_par_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE preference ADD CONSTRAINT FK_5D69B053FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF060BB6FE6');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0A96E5E09');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0412306B8');
        $this->addSql('ALTER TABLE covoiturage DROP FOREIGN KEY FK_28C79E8985C0B3BE');
        $this->addSql('ALTER TABLE covoiturage DROP FOREIGN KEY FK_28C79E894A4A3511');
        $this->addSql('ALTER TABLE covoiturage_user DROP FOREIGN KEY FK_F862CC4962671590');
        $this->addSql('ALTER TABLE covoiturage_user DROP FOREIGN KEY FK_F862CC49A76ED395');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9FC29C013');
        $this->addSql('ALTER TABLE message_contact DROP FOREIGN KEY FK_DCEADC34167FABE8');
        $this->addSql('ALTER TABLE preference DROP FOREIGN KEY FK_5D69B053FB88E14F');
        $this->addSql('DROP TABLE administrateur');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE covoiturage');
        $this->addSql('DROP TABLE covoiturage_user');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE message_contact');
        $this->addSql('DROP TABLE preference');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE vehicule');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
