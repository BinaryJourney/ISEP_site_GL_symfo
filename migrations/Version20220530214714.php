<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530214714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT NOT NULL, key_booker_user_id_id INT NOT NULL, key_house_owner_id_id INT NOT NULL, date_begin DATE NOT NULL, date_end DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E00CEDDE3755FFBD ON booking (key_booker_user_id_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE847104F6 ON booking (key_house_owner_id_id)');
        $this->addSql('CREATE TABLE house (id SERIAL NOT NULL, key_type_accommodation_capacity_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, date_begin DATE NOT NULL, date_end DATE NOT NULL, image_filename VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_67D5399D74CBAC1B ON house (key_type_accommodation_capacity_id)');
        $this->addSql('CREATE TABLE type_accommodation_capacity (id INT NOT NULL, capacity VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE type_sex (id INT NOT NULL, sex VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, key_type_sex_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, birthdate DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D6492294DADB ON "user" (key_type_sex_id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE3755FFBD FOREIGN KEY (key_booker_user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE847104F6 FOREIGN KEY (key_house_owner_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399D74CBAC1B FOREIGN KEY (key_type_accommodation_capacity_id) REFERENCES type_accommodation_capacity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6492294DADB FOREIGN KEY (key_type_sex_id) REFERENCES type_sex (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE house DROP CONSTRAINT FK_67D5399D74CBAC1B');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6492294DADB');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE3755FFBD');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE847104F6');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE house');
        $this->addSql('DROP TABLE type_accommodation_capacity');
        $this->addSql('DROP TABLE type_sex');
        $this->addSql('DROP TABLE "user"');
    }
}
