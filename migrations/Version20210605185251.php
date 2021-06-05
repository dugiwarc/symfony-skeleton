<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210605185251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, micro_post_id INT NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C11E37CEA (micro_post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_signals (post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EB45723E4B89032C (post_id), INDEX IDX_EB45723EA76ED395 (user_id), PRIMARY KEY(post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C11E37CEA FOREIGN KEY (micro_post_id) REFERENCES micro_post (id)');
        $this->addSql('ALTER TABLE post_signals ADD CONSTRAINT FK_EB45723E4B89032C FOREIGN KEY (post_id) REFERENCES micro_post (id)');
        $this->addSql('ALTER TABLE post_signals ADD CONSTRAINT FK_EB45723EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE post_signals');
    }
}
