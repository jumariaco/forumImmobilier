<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025000220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B36A99F74A');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B398DE13AC');
        $this->addSql('DROP INDEX IDX_AC6340B36A99F74A ON `like`');
        $this->addSql('DROP INDEX IDX_AC6340B398DE13AC ON `like`');
        $this->addSql('ALTER TABLE `like` ADD commentaire_id INT DEFAULT NULL, ADD publication_id INT DEFAULT NULL, DROP membre_id, DROP partenaire_id');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B338B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3BA9CD190 ON `like` (commentaire_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B338B217A7 ON `like` (publication_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3BA9CD190');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B338B217A7');
        $this->addSql('DROP INDEX IDX_AC6340B3BA9CD190 ON `like`');
        $this->addSql('DROP INDEX IDX_AC6340B338B217A7 ON `like`');
        $this->addSql('ALTER TABLE `like` ADD membre_id INT DEFAULT NULL, ADD partenaire_id INT DEFAULT NULL, DROP commentaire_id, DROP publication_id');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B36A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B398DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('CREATE INDEX IDX_AC6340B36A99F74A ON `like` (membre_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B398DE13AC ON `like` (partenaire_id)');
    }
}
