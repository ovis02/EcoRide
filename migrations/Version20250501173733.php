<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501173733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis ADD gere_par_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0412306B8 FOREIGN KEY (gere_par_id) REFERENCES employe (id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF0412306B8 ON avis (gere_par_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0412306B8');
        $this->addSql('DROP INDEX IDX_8F91ABF0412306B8 ON avis');
        $this->addSql('ALTER TABLE avis DROP gere_par_id');
    }
}
