<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501120643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE covoiturage ADD vehicule_id INT NOT NULL');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E894A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicule (id)');
        $this->addSql('CREATE INDEX IDX_28C79E894A4A3511 ON covoiturage (vehicule_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE covoiturage DROP FOREIGN KEY FK_28C79E894A4A3511');
        $this->addSql('DROP INDEX IDX_28C79E894A4A3511 ON covoiturage');
        $this->addSql('ALTER TABLE covoiturage DROP vehicule_id');
    }
}
