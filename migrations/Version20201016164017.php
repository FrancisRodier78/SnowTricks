<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201016164017 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, figure_id INT NOT NULL, creation_date DATETIME NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_67F068BCF675F31B (author_id), INDEX IDX_67F068BC5C011B5 (figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, figure_picture_id INT DEFAULT NULL, figure_video_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, caption VARCHAR(255) NOT NULL, boolean_image_video SMALLINT NOT NULL, INDEX IDX_D8698A76C2272BCF (figure_picture_id), INDEX IDX_D8698A762A7DBDFB (figure_video_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE figure (id INT AUTO_INCREMENT NOT NULL, author_id_id INT NOT NULL, groupe_id INT DEFAULT NULL, figure_name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image_defaut VARCHAR(255) NOT NULL, groupe_name VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, modif_date DATETIME NOT NULL, INDEX IDX_2F57B37A69CCBE9A (author_id_id), INDEX IDX_2F57B37A7A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, groupe_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, introduction VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC5C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76C2272BCF FOREIGN KEY (figure_picture_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A762A7DBDFB FOREIGN KEY (figure_video_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37A69CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37A7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC5C011B5');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76C2272BCF');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A762A7DBDFB');
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37A7A45358C');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCF675F31B');
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37A69CCBE9A');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE figure');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE user');
    }
}
