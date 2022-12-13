<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221212105303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, position INT NOT NULL, moderation_only TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE circuit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, city VARCHAR(100) NOT NULL, country VARCHAR(100) NOT NULL, image VARCHAR(255) NOT NULL, length INT NOT NULL, height INT NOT NULL, difficulty SMALLINT NOT NULL, description LONGTEXT NOT NULL, maps_location VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, date INT NOT NULL, last_update INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_472B783AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_gallery (id INT AUTO_INCREMENT NOT NULL, gallery_id INT NOT NULL, user_id INT NOT NULL, date INT NOT NULL, last_edited INT DEFAULT NULL, content LONGTEXT NOT NULL, flagged INT NOT NULL, reactions LONGTEXT DEFAULT NULL, INDEX IDX_D7A342364E7AF8F (gallery_id), INDEX IDX_D7A34236A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_topic (id BIGINT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, topic_id INT NOT NULL, date INT NOT NULL, last_edited INT DEFAULT NULL, flagged INT NOT NULL, content LONGTEXT NOT NULL, reactions LONGTEXT DEFAULT NULL, INDEX IDX_62E621F0A76ED395 (user_id), INDEX IDX_62E621F01F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id BIGINT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, user_id INT NOT NULL, other_user_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, date INT NOT NULL, badge INT DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_BF5476CA1F55203D (topic_id), INDEX IDX_BF5476CAA76ED395 (user_id), INDEX IDX_BF5476CAB4334DF9 (other_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE peloton (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, city VARCHAR(100) NOT NULL, country VARCHAR(100) NOT NULL, image VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, maps_location VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repair (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, city VARCHAR(100) NOT NULL, country VARCHAR(100) NOT NULL, image VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, maps_location VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_category (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, last_message_id BIGINT DEFAULT NULL, name VARCHAR(100) NOT NULL, position INT NOT NULL, description VARCHAR(255) DEFAULT NULL, messages_nb INT NOT NULL, topics_nb INT NOT NULL, INDEX IDX_BCE3F79812469DE2 (category_id), UNIQUE INDEX UNIQ_BCE3F798BA0E79C3 (last_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taken_usernames (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, expiration_date INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic (id INT AUTO_INCREMENT NOT NULL, sub_category_id INT NOT NULL, last_message_id BIGINT NOT NULL, user_id INT NOT NULL, first_message_id BIGINT NOT NULL, title VARCHAR(100) NOT NULL, slug VARCHAR(255) NOT NULL, messages_nb INT NOT NULL, locked TINYINT(1) NOT NULL, pinned TINYINT(1) NOT NULL, views_nb INT NOT NULL, date INT NOT NULL, INDEX IDX_9D40DE1BF7BFE87C (sub_category_id), UNIQUE INDEX UNIQ_9D40DE1BBA0E79C3 (last_message_id), INDEX IDX_9D40DE1BA76ED395 (user_id), INDEX IDX_9D40DE1BC2E2722E (first_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic_user (topic_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B578B7FC1F55203D (topic_id), INDEX IDX_B578B7FCA76ED395 (user_id), PRIMARY KEY(topic_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(16) NOT NULL, city VARCHAR(100) DEFAULT NULL, country VARCHAR(100) DEFAULT NULL, signature VARCHAR(255) DEFAULT NULL, notifs_nb INT NOT NULL, photo VARCHAR(255) DEFAULT NULL, messages_nb INT NOT NULL, date INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message_gallery ADD CONSTRAINT FK_D7A342364E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE message_gallery ADD CONSTRAINT FK_D7A34236A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message_topic ADD CONSTRAINT FK_62E621F0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message_topic ADD CONSTRAINT FK_62E621F01F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAB4334DF9 FOREIGN KEY (other_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sub_category ADD CONSTRAINT FK_BCE3F79812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE sub_category ADD CONSTRAINT FK_BCE3F798BA0E79C3 FOREIGN KEY (last_message_id) REFERENCES message_topic (id)');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1BF7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id)');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1BBA0E79C3 FOREIGN KEY (last_message_id) REFERENCES message_topic (id)');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1BC2E2722E FOREIGN KEY (first_message_id) REFERENCES message_topic (id)');
        $this->addSql('ALTER TABLE topic_user ADD CONSTRAINT FK_B578B7FC1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic_user ADD CONSTRAINT FK_B578B7FCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gallery DROP FOREIGN KEY FK_472B783AA76ED395');
        $this->addSql('ALTER TABLE message_gallery DROP FOREIGN KEY FK_D7A342364E7AF8F');
        $this->addSql('ALTER TABLE message_gallery DROP FOREIGN KEY FK_D7A34236A76ED395');
        $this->addSql('ALTER TABLE message_topic DROP FOREIGN KEY FK_62E621F0A76ED395');
        $this->addSql('ALTER TABLE message_topic DROP FOREIGN KEY FK_62E621F01F55203D');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA1F55203D');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAB4334DF9');
        $this->addSql('ALTER TABLE sub_category DROP FOREIGN KEY FK_BCE3F79812469DE2');
        $this->addSql('ALTER TABLE sub_category DROP FOREIGN KEY FK_BCE3F798BA0E79C3');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1BF7BFE87C');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1BBA0E79C3');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1BA76ED395');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1BC2E2722E');
        $this->addSql('ALTER TABLE topic_user DROP FOREIGN KEY FK_B578B7FC1F55203D');
        $this->addSql('ALTER TABLE topic_user DROP FOREIGN KEY FK_B578B7FCA76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE circuit');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE message_gallery');
        $this->addSql('DROP TABLE message_topic');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE peloton');
        $this->addSql('DROP TABLE repair');
        $this->addSql('DROP TABLE sub_category');
        $this->addSql('DROP TABLE taken_usernames');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP TABLE topic_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
