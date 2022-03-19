<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220314121218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE final_grade (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, class_id INT DEFAULT NULL, grade VARCHAR(255) NOT NULL, INDEX IDX_5842FDA4CB944F1A (student_id), INDEX IDX_5842FDA4EA000B10 (class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_grade (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, class_id INT DEFAULT NULL, grade VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D16DD7A9CB944F1A (student_id), UNIQUE INDEX UNIQ_D16DD7A9EA000B10 (class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE final_grade ADD CONSTRAINT FK_5842FDA4CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE final_grade ADD CONSTRAINT FK_5842FDA4EA000B10 FOREIGN KEY (class_id) REFERENCES classes (id)');
        $this->addSql('ALTER TABLE student_grade ADD CONSTRAINT FK_D16DD7A9CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE student_grade ADD CONSTRAINT FK_D16DD7A9EA000B10 FOREIGN KEY (class_id) REFERENCES classes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE final_grade');
        $this->addSql('DROP TABLE student_grade');
    }
}
