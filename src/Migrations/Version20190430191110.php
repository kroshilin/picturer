<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430191110 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, poll_id INT DEFAULT NULL, tele_user_id VARCHAR(255) DEFAULT NULL, date_time DATETIME NOT NULL, INDEX IDX_5A1085643C947C0F (poll_id), INDEX IDX_5A10856416318B0A (tele_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teleuser (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (name VARCHAR(255) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poll_template (id INT NOT NULL, name VARCHAR(255) NOT NULL, template LONGTEXT NOT NULL, use_picture TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poll (id INT AUTO_INCREMENT NOT NULL, chat_id INT DEFAULT NULL, poll_template_id INT DEFAULT NULL, isbn VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, author VARCHAR(255) NOT NULL, publication_date DATETIME NOT NULL, INDEX IDX_84BCFA451A9A7125 (chat_id), INDEX IDX_84BCFA4537B878BA (poll_template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, author VARCHAR(255) NOT NULL, publication_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085643C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856416318B0A FOREIGN KEY (tele_user_id) REFERENCES teleuser (id)');
        $this->addSql('ALTER TABLE poll ADD CONSTRAINT FK_84BCFA451A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE poll ADD CONSTRAINT FK_84BCFA4537B878BA FOREIGN KEY (poll_template_id) REFERENCES poll (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A10856416318B0A');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085643C947C0F');
        $this->addSql('ALTER TABLE poll DROP FOREIGN KEY FK_84BCFA4537B878BA');
        $this->addSql('ALTER TABLE poll DROP FOREIGN KEY FK_84BCFA451A9A7125');
        $this->addSql('DROP TABLE vote');
        $this->addSql('DROP TABLE teleuser');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE poll_template');
        $this->addSql('DROP TABLE poll');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE picture');
    }
}
