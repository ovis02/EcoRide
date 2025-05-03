<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501182906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message_contact ADD traite_par_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message_contact ADD CONSTRAINT FK_DCEADC34167FABE8 FOREIGN KEY (traite_par_id) REFERENCES employe (id)');
        $this->addSql('CREATE INDEX IDX_DCEADC34167FABE8 ON message_contact (traite_par_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message_contact DROP FOREIGN KEY FK_DCEADC34167FABE8');
        $this->addSql('DROP INDEX IDX_DCEADC34167FABE8 ON message_contact');
        $this->addSql('ALTER TABLE message_contact DROP traite_par_id');
    }
}
