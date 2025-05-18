<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250518065223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE covoiturage_validations (covoiturage_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3AEA27B162671590 (covoiturage_id), INDEX IDX_3AEA27B1A76ED395 (user_id), PRIMARY KEY(covoiturage_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE covoiturage_validations ADD CONSTRAINT FK_3AEA27B162671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covoiturage_validations ADD CONSTRAINT FK_3AEA27B1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covoiturage ADD etat VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE covoiturage_validations DROP FOREIGN KEY FK_3AEA27B162671590');
        $this->addSql('ALTER TABLE covoiturage_validations DROP FOREIGN KEY FK_3AEA27B1A76ED395');
        $this->addSql('DROP TABLE covoiturage_validations');
        $this->addSql('ALTER TABLE covoiturage DROP etat');
    }
}
