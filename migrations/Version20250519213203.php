<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519213203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE incident (id INT AUTO_INCREMENT NOT NULL, covoiturage_id INT NOT NULL, signal_par_id INT NOT NULL, description LONGTEXT NOT NULL, date_de_signalement DATETIME NOT NULL, traite TINYINT(1) NOT NULL, INDEX IDX_3D03A11A62671590 (covoiturage_id), INDEX IDX_3D03A11AE5F7296D (signal_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE incident ADD CONSTRAINT FK_3D03A11A62671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id)');
        $this->addSql('ALTER TABLE incident ADD CONSTRAINT FK_3D03A11AE5F7296D FOREIGN KEY (signal_par_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11A62671590');
        $this->addSql('ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11AE5F7296D');
        $this->addSql('DROP TABLE incident');
    }
}
