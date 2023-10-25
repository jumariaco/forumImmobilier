<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231024222550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE membre_domaine (membre_id INT NOT NULL, domaine_id INT NOT NULL, INDEX IDX_CA8808716A99F74A (membre_id), INDEX IDX_CA8808714272FC9F (domaine_id), PRIMARY KEY(membre_id, domaine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partenaire_domaine (partenaire_id INT NOT NULL, domaine_id INT NOT NULL, INDEX IDX_675EF41B98DE13AC (partenaire_id), INDEX IDX_675EF41B4272FC9F (domaine_id), PRIMARY KEY(partenaire_id, domaine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication_domaine (publication_id INT NOT NULL, domaine_id INT NOT NULL, INDEX IDX_7026DCF038B217A7 (publication_id), INDEX IDX_7026DCF04272FC9F (domaine_id), PRIMARY KEY(publication_id, domaine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_F7129A803AD8644E (user_source), INDEX IDX_F7129A80233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE membre_domaine ADD CONSTRAINT FK_CA8808716A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE membre_domaine ADD CONSTRAINT FK_CA8808714272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partenaire_domaine ADD CONSTRAINT FK_675EF41B98DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partenaire_domaine ADD CONSTRAINT FK_675EF41B4272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_domaine ADD CONSTRAINT FK_7026DCF038B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_domaine ADD CONSTRAINT FK_7026DCF04272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD publication_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_67F068BC38B217A7 ON commentaire (publication_id)');
        $this->addSql('CREATE INDEX IDX_67F068BCA76ED395 ON commentaire (user_id)');
        $this->addSql('ALTER TABLE domaine ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `like` ADD publication_id INT DEFAULT NULL, ADD commentaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B338B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
        $this->addSql('CREATE INDEX IDX_AC6340B338B217A7 ON `like` (publication_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3BA9CD190 ON `like` (commentaire_id)');
        $this->addSql('ALTER TABLE membre ADD titre_id INT NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB29D54FAE5E FOREIGN KEY (titre_id) REFERENCES titre (id)');
        $this->addSql('CREATE INDEX IDX_F6B4FB29D54FAE5E ON membre (titre_id)');
        $this->addSql('ALTER TABLE partenaire ADD titre_id INT NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE partenaire ADD CONSTRAINT FK_32FFA373D54FAE5E FOREIGN KEY (titre_id) REFERENCES titre (id)');
        $this->addSql('CREATE INDEX IDX_32FFA373D54FAE5E ON partenaire (titre_id)');
        $this->addSql('ALTER TABLE publication ADD user_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AF3C6779A76ED395 ON publication (user_id)');
        $this->addSql('ALTER TABLE titre ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD partenaire_id INT DEFAULT NULL, ADD membre_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64998DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64998DE13AC ON user (partenaire_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496A99F74A ON user (membre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE membre_domaine DROP FOREIGN KEY FK_CA8808716A99F74A');
        $this->addSql('ALTER TABLE membre_domaine DROP FOREIGN KEY FK_CA8808714272FC9F');
        $this->addSql('ALTER TABLE partenaire_domaine DROP FOREIGN KEY FK_675EF41B98DE13AC');
        $this->addSql('ALTER TABLE partenaire_domaine DROP FOREIGN KEY FK_675EF41B4272FC9F');
        $this->addSql('ALTER TABLE publication_domaine DROP FOREIGN KEY FK_7026DCF038B217A7');
        $this->addSql('ALTER TABLE publication_domaine DROP FOREIGN KEY FK_7026DCF04272FC9F');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A80233D34C1');
        $this->addSql('DROP TABLE membre_domaine');
        $this->addSql('DROP TABLE partenaire_domaine');
        $this->addSql('DROP TABLE publication_domaine');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC38B217A7');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('DROP INDEX IDX_67F068BC38B217A7 ON commentaire');
        $this->addSql('DROP INDEX IDX_67F068BCA76ED395 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP publication_id, DROP user_id, DROP created_at, DROP updated_at, DROP deleted_at');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB29D54FAE5E');
        $this->addSql('DROP INDEX IDX_F6B4FB29D54FAE5E ON membre');
        $this->addSql('ALTER TABLE membre DROP titre_id, DROP created_at, DROP updated_at, DROP deleted_at');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A76ED395');
        $this->addSql('DROP INDEX IDX_AF3C6779A76ED395 ON publication');
        $this->addSql('ALTER TABLE publication DROP user_id, DROP created_at, DROP updated_at, DROP deleted_at');
        $this->addSql('ALTER TABLE titre DROP created_at, DROP updated_at, DROP deleted_at');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B338B217A7');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3BA9CD190');
        $this->addSql('DROP INDEX IDX_AC6340B338B217A7 ON `like`');
        $this->addSql('DROP INDEX IDX_AC6340B3BA9CD190 ON `like`');
        $this->addSql('ALTER TABLE `like` DROP publication_id, DROP commentaire_id');
        $this->addSql('ALTER TABLE partenaire DROP FOREIGN KEY FK_32FFA373D54FAE5E');
        $this->addSql('DROP INDEX IDX_32FFA373D54FAE5E ON partenaire');
        $this->addSql('ALTER TABLE partenaire DROP titre_id, DROP created_at, DROP updated_at, DROP deleted_at');
        $this->addSql('ALTER TABLE domaine DROP created_at, DROP updated_at, DROP deleted_at');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64998DE13AC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496A99F74A');
        $this->addSql('DROP INDEX UNIQ_8D93D64998DE13AC ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6496A99F74A ON user');
        $this->addSql('ALTER TABLE user DROP partenaire_id, DROP membre_id, DROP created_at, DROP updated_at, DROP deleted_at');
    }
}
