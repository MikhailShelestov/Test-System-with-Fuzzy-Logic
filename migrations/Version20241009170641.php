<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241009170641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE answer (id INT NOT NULL, question_id INT NOT NULL, value VARCHAR(255) NOT NULL, is_right_answer BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');
        $this->addSql('CREATE TABLE question (id INT NOT NULL, statement VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        // I used raw sql insert instead of dump, because it easy to do and there is already 7AM and I wanna sleep. Sorry -_-
        $this->addSql("INSERT INTO question (id, statement) VALUES 
        (1, '1 + 1 ='),
        (2, '2 + 2 ='),
        (3, '3 + 3 ='),
        (4, '4 + 4 ='),
        (5, '5 + 5 ='),
        (6, '6 + 6 ='),
        (7, '7 + 7 ='),
        (8, '8 + 8 ='),
        (9, '9 + 9 ='),
        (10, '10 + 10 =')
    ");

        $this->addSql("INSERT INTO answer (id, question_id, value, is_right_answer) VALUES 
        (1, 1, '3', FALSE),
        (2, 1, '2', TRUE),
        (3, 1, '0', FALSE),

        (4, 2, '4', TRUE),
        (5, 2, '3 + 1', TRUE),
        (6, 2, '10', FALSE),

        (7, 3, '1 + 5', TRUE),
        (8, 3, '1', FALSE),
        (9, 3, '6', TRUE),
        (10, 3, '2 + 4', TRUE),

        (11, 4, '8', TRUE),
        (12, 4, '4', FALSE),
        (13, 4, '0', FALSE),
        (14, 4, '0 + 8', TRUE),

        (15, 5, '6', FALSE),
        (16, 5, '18', FALSE),
        (17, 5, '10', TRUE),
        (18, 5, '9', FALSE),
        (19, 5, '0', FALSE),

        (20, 6, '3', FALSE),
        (21, 6, '9', FALSE),
        (22, 6, '0', FALSE),
        (23, 6, '12', TRUE),
        (24, 6, '5 + 7', TRUE),

        (25, 7, '5', FALSE),
        (26, 7, '14', TRUE),

        (27, 8, '16', TRUE),
        (28, 8, '12', FALSE),
        (29, 8, '9', FALSE),
        (30, 8, '5', FALSE),

        (31, 9, '18', TRUE),
        (32, 9, '9', FALSE),
        (33, 9, '17 + 1', TRUE),
        (34, 9, '2 + 16', TRUE),

        (35, 10, '0', FALSE),
        (36, 10, '2', FALSE),
        (37, 10, '8', FALSE),
        (38, 10, '20', TRUE)
    ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE question_id_seq CASCADE');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A251E27F6BF');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE question');
    }
}
