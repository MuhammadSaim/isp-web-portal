<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190205063904 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json_array)\', remember_token VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departments (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, department VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_16AEB8D4989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, department_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, course VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, modifies_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_A9A55A4C989D9B62 (slug), INDEX IDX_A9A55A4CAE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_details (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, department_id INT DEFAULT NULL, course_id INT DEFAULT NULL, reg_no VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, bio LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_9F10BE7241986983 (reg_no), UNIQUE INDEX UNIQ_9F10BE72A76ED395 (user_id), UNIQUE INDEX UNIQ_9F10BE72AE80F5DF (department_id), UNIQUE INDEX UNIQ_9F10BE72591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4CAE80F5DF FOREIGN KEY (department_id) REFERENCES departments (id)');
        $this->addSql('ALTER TABLE student_details ADD CONSTRAINT FK_9F10BE72A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE student_details ADD CONSTRAINT FK_9F10BE72AE80F5DF FOREIGN KEY (department_id) REFERENCES departments (id)');
        $this->addSql('ALTER TABLE student_details ADD CONSTRAINT FK_9F10BE72591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE student_details DROP FOREIGN KEY FK_9F10BE72A76ED395');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4CAE80F5DF');
        $this->addSql('ALTER TABLE student_details DROP FOREIGN KEY FK_9F10BE72AE80F5DF');
        $this->addSql('ALTER TABLE student_details DROP FOREIGN KEY FK_9F10BE72591CC992');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE departments');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE student_details');
    }
}
