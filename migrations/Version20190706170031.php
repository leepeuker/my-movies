<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190706170031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE production_company (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(2) DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE production_company_movie (production_company_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_C053EDEEF13ABE4D (production_company_id), INDEX IDX_C053EDEE8F93B6FC (movie_id), PRIMARY KEY(production_company_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE production_company_movie ADD CONSTRAINT FK_C053EDEEF13ABE4D FOREIGN KEY (production_company_id) REFERENCES production_company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE production_company_movie ADD CONSTRAINT FK_C053EDEE8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE production_company_movie DROP FOREIGN KEY FK_C053EDEEF13ABE4D');
        $this->addSql('DROP TABLE production_company');
        $this->addSql('DROP TABLE production_company_movie');
    }
}
