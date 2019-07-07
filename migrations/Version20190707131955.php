<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190707131955 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP INDEX unique_inde_watch_date ON watch_date');
        $this->addSql('ALTER TABLE movie_production_company DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE movie_production_company ADD PRIMARY KEY (movie_id, production_company_id)');
        $this->addSql('ALTER TABLE movie_production_company RENAME INDEX idx_c053edee8f93b6fc TO IDX_54F6AE3C8F93B6FC');
        $this->addSql('ALTER TABLE movie_production_company RENAME INDEX idx_c053edeef13abe4d TO IDX_54F6AE3CF13ABE4D');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE person');
        $this->addSql('ALTER TABLE movie_production_company DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE movie_production_company ADD PRIMARY KEY (production_company_id, movie_id)');
        $this->addSql('ALTER TABLE movie_production_company RENAME INDEX idx_54f6ae3c8f93b6fc TO IDX_C053EDEE8F93B6FC');
        $this->addSql('ALTER TABLE movie_production_company RENAME INDEX idx_54f6ae3cf13abe4d TO IDX_C053EDEEF13ABE4D');
        $this->addSql('CREATE UNIQUE INDEX unique_inde_watch_date ON watch_date (watch_date, movie_id)');
    }
}
