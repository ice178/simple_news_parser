<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210629191657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE news_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE parser_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE parser_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE news (id SERIAL NOT NULL, parser_id INT NOT NULL, title VARCHAR(2048) NOT NULL, description TEXT NOT NULL, publish_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, author VARCHAR(2048) NOT NULL, image VARCHAR(2048) NOT NULL, status VARCHAR(255) NOT NULL, link VARCHAR(2048) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, external_id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1DD39950F54E453B ON news (parser_id)');
        $this->addSql('CREATE INDEX idx_external_id ON news (external_id)');
        $this->addSql('CREATE TABLE parser (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(2048) NOT NULL, class VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE parser_log (id SERIAL NOT NULL, parser_id INT NOT NULL, request_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, request_method VARCHAR(32) NOT NULL, request_url VARCHAR(2048) NOT NULL, response_http_code INT NOT NULL, response_body TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5515A737F54E453B ON parser_log (parser_id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950F54E453B FOREIGN KEY (parser_id) REFERENCES parser (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE parser_log ADD CONSTRAINT FK_5515A737F54E453B FOREIGN KEY (parser_id) REFERENCES parser (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE news DROP CONSTRAINT FK_1DD39950F54E453B');
        $this->addSql('ALTER TABLE parser_log DROP CONSTRAINT FK_5515A737F54E453B');
        $this->addSql('DROP SEQUENCE news_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE parser_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE parser_log_id_seq CASCADE');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE parser');
        $this->addSql('DROP TABLE parser_log');
    }
}
