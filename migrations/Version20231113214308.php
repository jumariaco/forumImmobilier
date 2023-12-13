<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231113214308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD enfant_id INT DEFAULT NULL, CHANGE publication_id publication_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC450D2529 FOREIGN KEY (enfant_id) REFERENCES commentaire (id)');
        $this->addSql('CREATE INDEX IDX_67F068BC450D2529 ON commentaire (enfant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC450D2529');
        $this->addSql('DROP INDEX IDX_67F068BC450D2529 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP enfant_id, CHANGE publication_id publication_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
    }
}
