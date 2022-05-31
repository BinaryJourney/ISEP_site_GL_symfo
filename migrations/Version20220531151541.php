<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531151541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE house_id_seq1 CASCADE');
        $this->addSql('CREATE SEQUENCE liste_ville_france_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE liste_ville_france (id INT NOT NULL, name VARCHAR(255) NOT NULL, department VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE house ALTER id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE liste_ville_france_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE house_id_seq1 INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE liste_ville_france');
        $this->addSql('CREATE SEQUENCE house_id_seq');
        $this->addSql('SELECT setval(\'house_id_seq\', (SELECT MAX(id) FROM house))');
        $this->addSql('ALTER TABLE house ALTER id SET DEFAULT nextval(\'house_id_seq\')');
    }
}
