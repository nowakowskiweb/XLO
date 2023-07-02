<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531170502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE announcements (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, voivodeship VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL, condition_type VARCHAR(50) NOT NULL, INDEX IDX_F422A9DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE announcements_categories (announcement_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_3A00A077913AEA17 (announcement_id), INDEX IDX_3A00A07712469DE2 (category_id), PRIMARY KEY(announcement_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE announcements_images (announcement_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_4B54733A913AEA17 (announcement_id), INDEX IDX_4B54733A3DA5256D (image_id), PRIMARY KEY(announcement_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, login VARCHAR(10) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(70) DEFAULT NULL, verified TINYINT(1) NOT NULL, email_auth_enabled TINYINT(1) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, auth_code VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649AA08CB10 (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE announcements ADD CONSTRAINT FK_F422A9DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE announcements_categories ADD CONSTRAINT FK_3A00A077913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcements (id)');
        $this->addSql('ALTER TABLE announcements_categories ADD CONSTRAINT FK_3A00A07712469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE announcements_images ADD CONSTRAINT FK_4B54733A913AEA17 FOREIGN KEY (announcement_id) REFERENCES announcements (id)');
        $this->addSql('ALTER TABLE announcements_images ADD CONSTRAINT FK_4B54733A3DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE announcements DROP FOREIGN KEY FK_F422A9DA76ED395');
        $this->addSql('ALTER TABLE announcements_categories DROP FOREIGN KEY FK_3A00A077913AEA17');
        $this->addSql('ALTER TABLE announcements_categories DROP FOREIGN KEY FK_3A00A07712469DE2');
        $this->addSql('ALTER TABLE announcements_images DROP FOREIGN KEY FK_4B54733A913AEA17');
        $this->addSql('ALTER TABLE announcements_images DROP FOREIGN KEY FK_4B54733A3DA5256D');
        $this->addSql('DROP TABLE announcements');
        $this->addSql('DROP TABLE announcements_categories');
        $this->addSql('DROP TABLE announcements_images');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
