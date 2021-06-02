<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210602212756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification ADD user_id INT DEFAULT NULL, ADD micro_post_id INT DEFAULT NULL, ADD liked_by_id INT DEFAULT NULL, ADD seen TINYINT(1) NOT NULL, ADD discr VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA11E37CEA FOREIGN KEY (micro_post_id) REFERENCES micro_post (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAB4622EC2 FOREIGN KEY (liked_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA11E37CEA ON notification (micro_post_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAB4622EC2 ON notification (liked_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA11E37CEA');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAB4622EC2');
        $this->addSql('DROP INDEX IDX_BF5476CAA76ED395 ON notification');
        $this->addSql('DROP INDEX IDX_BF5476CA11E37CEA ON notification');
        $this->addSql('DROP INDEX IDX_BF5476CAB4622EC2 ON notification');
        $this->addSql('ALTER TABLE notification DROP user_id, DROP micro_post_id, DROP liked_by_id, DROP seen, DROP discr');
    }
}
