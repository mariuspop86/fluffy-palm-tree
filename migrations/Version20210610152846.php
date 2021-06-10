<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210610152846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heatmap (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, link_type_id INT NOT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_51EF598A9395C3F3 (customer_id), INDEX IDX_51EF598AB82AD666 (link_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE link_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE heatmap ADD CONSTRAINT FK_51EF598A9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE heatmap ADD CONSTRAINT FK_51EF598AB82AD666 FOREIGN KEY (link_type_id) REFERENCES link_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE heatmap DROP FOREIGN KEY FK_51EF598A9395C3F3');
        $this->addSql('ALTER TABLE heatmap DROP FOREIGN KEY FK_51EF598AB82AD666');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE heatmap');
        $this->addSql('DROP TABLE link_type');
    }
}
