<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010025202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE test_result_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE test_result_answers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE test_result (id INT NOT NULL, token VARCHAR(255) NOT NULL, score DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE test_result_answers (id INT NOT NULL, question_id INT NOT NULL, answer_id INT DEFAULT NULL, test_result_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4DA5BCEF1E27F6BF ON test_result_answers (question_id)');
        $this->addSql('CREATE INDEX IDX_4DA5BCEFAA334807 ON test_result_answers (answer_id)');
        $this->addSql('CREATE INDEX IDX_4DA5BCEF853A2189 ON test_result_answers (test_result_id)');
        $this->addSql('ALTER TABLE test_result_answers ADD CONSTRAINT FK_4DA5BCEF1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_result_answers ADD CONSTRAINT FK_4DA5BCEFAA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE test_result_answers ADD CONSTRAINT FK_4DA5BCEF853A2189 FOREIGN KEY (test_result_id) REFERENCES test_result (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE test_result_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE test_result_answers_id_seq CASCADE');
        $this->addSql('ALTER TABLE test_result_answers DROP CONSTRAINT FK_4DA5BCEF1E27F6BF');
        $this->addSql('ALTER TABLE test_result_answers DROP CONSTRAINT FK_4DA5BCEFAA334807');
        $this->addSql('ALTER TABLE test_result_answers DROP CONSTRAINT FK_4DA5BCEF853A2189');
        $this->addSql('DROP TABLE test_result');
        $this->addSql('DROP TABLE test_result_answers');
    }
}
