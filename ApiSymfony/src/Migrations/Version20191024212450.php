<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191024212450 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compte CHANGE partenaire_id partenaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD code INT DEFAULT NULL, ADD montant INT NOT NULL, ADD cometat INT NOT NULL, ADD comsystem INT NOT NULL, ADD comenvoie INT NOT NULL, ADD type VARCHAR(255) NOT NULL, ADD frais INT NOT NULL, ADD comretrait INT NOT NULL, ADD nom_e VARCHAR(255) NOT NULL, ADD prenom_e VARCHAR(255) NOT NULL, ADD tel_e VARCHAR(255) NOT NULL, ADD nom_ex VARCHAR(255) NOT NULL, ADD prenom_ex VARCHAR(255) NOT NULL, ADD adresse_ex VARCHAR(255) NOT NULL, ADD telephone_ex INT NOT NULL, ADD cni_ex INT DEFAULT NULL, ADD date_retrait DATETIME DEFAULT NULL, DROP envoie, DROP retrait, CHANGE datetransaction date_envoie DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD compte_id INT DEFAULT NULL, ADD image_name VARCHAR(255) NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649F2C56620 ON user (compte_id)');
        $this->addSql('ALTER TABLE tarifs ADD valeur INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compte CHANGE partenaire_id partenaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tarifs DROP valeur');
        $this->addSql('ALTER TABLE transaction ADD envoie INT NOT NULL, ADD retrait INT NOT NULL, DROP code, DROP montant, DROP cometat, DROP comsystem, DROP comenvoie, DROP type, DROP frais, DROP comretrait, DROP nom_e, DROP prenom_e, DROP tel_e, DROP nom_ex, DROP prenom_ex, DROP adresse_ex, DROP telephone_ex, DROP cni_ex, DROP date_retrait, CHANGE date_envoie datetransaction DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F2C56620');
        $this->addSql('DROP INDEX IDX_8D93D649F2C56620 ON user');
        $this->addSql('ALTER TABLE user DROP compte_id, DROP image_name, DROP updated_at');
    }
}
