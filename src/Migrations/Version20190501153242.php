<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190501153242 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tag CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085643C947C0F');
        $this->addSql('ALTER TABLE poll_template CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('alter table poll drop foreign key FK_84BCFA4537B878BA');
        $this->addSql('ALTER TABLE poll ADD image_id INT DEFAULT NULL, DROP isbn, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE poll ADD CONSTRAINT FK_84BCFA453DA5256D FOREIGN KEY (image_id) REFERENCES picture (id)');
        $this->addSql('CREATE INDEX IDX_84BCFA453DA5256D ON poll (image_id)');
        $this->addSql('ALTER TABLE picture ADD file_path VARCHAR(255) DEFAULT NULL, DROP name, DROP title, DROP description, DROP author, DROP publication_date');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE picture ADD name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD description LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, ADD author VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD publication_date DATETIME NOT NULL, DROP file_path');
        $this->addSql('ALTER TABLE poll DROP FOREIGN KEY FK_84BCFA453DA5256D');
        $this->addSql('DROP INDEX IDX_84BCFA453DA5256D ON poll');
        $this->addSql('ALTER TABLE poll ADD isbn VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP image_id, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE poll_template CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE tag CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
