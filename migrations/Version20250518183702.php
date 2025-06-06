<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250518183702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE preference DROP INDEX IDX_5D69B053FB88E14F, ADD UNIQUE INDEX UNIQ_5D69B053FB88E14F (utilisateur_id)');
        $this->addSql('ALTER TABLE preference DROP FOREIGN KEY FK_5D69B053FB88E14F');
        $this->addSql('ALTER TABLE preference ADD CONSTRAINT FK_5D69B053FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE preference DROP INDEX UNIQ_5D69B053FB88E14F, ADD INDEX IDX_5D69B053FB88E14F (utilisateur_id)');
        $this->addSql('ALTER TABLE preference DROP FOREIGN KEY FK_5D69B053FB88E14F');
        $this->addSql('ALTER TABLE preference ADD CONSTRAINT FK_5D69B053FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
    }
}
