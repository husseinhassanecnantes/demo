<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240522223546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_trainer (course_id INT NOT NULL, trainer_id INT NOT NULL, INDEX IDX_CDD60DCC591CC992 (course_id), INDEX IDX_CDD60DCCFB08EDF6 (trainer_id), PRIMARY KEY(course_id, trainer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_trainer ADD CONSTRAINT FK_CDD60DCC591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_trainer ADD CONSTRAINT FK_CDD60DCCFB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trainer_course DROP FOREIGN KEY FK_25D0350D591CC992');
        $this->addSql('ALTER TABLE trainer_course DROP FOREIGN KEY FK_25D0350DFB08EDF6');
        $this->addSql('DROP TABLE trainer_course');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trainer_course (trainer_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_25D0350DFB08EDF6 (trainer_id), INDEX IDX_25D0350D591CC992 (course_id), PRIMARY KEY(trainer_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE trainer_course ADD CONSTRAINT FK_25D0350D591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trainer_course ADD CONSTRAINT FK_25D0350DFB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainer (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_trainer DROP FOREIGN KEY FK_CDD60DCC591CC992');
        $this->addSql('ALTER TABLE course_trainer DROP FOREIGN KEY FK_CDD60DCCFB08EDF6');
        $this->addSql('DROP TABLE course_trainer');
    }
}
