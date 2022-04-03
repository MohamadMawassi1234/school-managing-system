<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220401064018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE student_grade');
        $this->addSql('ALTER TABLE classes ADD image_updated TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE course ADD image_updated TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD image_updated TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, grade VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, studentId INT DEFAULT NULL, courseId INT DEFAULT NULL, classId INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE student_grade (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, class_id INT DEFAULT NULL, grade VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_D16DD7A9EA000B10 (class_id), UNIQUE INDEX UNIQ_D16DD7A9CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE student_grade ADD CONSTRAINT FK_D16DD7A9CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE student_grade ADD CONSTRAINT FK_D16DD7A9EA000B10 FOREIGN KEY (class_id) REFERENCES classes (id)');
        $this->addSql('ALTER TABLE classes DROP image_updated');
        $this->addSql('ALTER TABLE course DROP image_updated');
        $this->addSql('ALTER TABLE student DROP image_updated');
    }
}
