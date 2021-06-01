<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210601175753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE following (user_id INT NOT NULL, following_user_id INT NOT NULL, INDEX IDX_71BF8DE3A76ED395 (user_id), INDEX IDX_71BF8DE31896F387 (following_user_id), PRIMARY KEY(user_id, following_user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_preferences (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE31896F387 FOREIGN KEY (following_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD preferences_id INT DEFAULT NULL, ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', ADD confirmation_token VARCHAR(30) DEFAULT NULL, ADD enabled TINYINT(1) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE full_name full_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497CCD6FB7 FOREIGN KEY (preferences_id) REFERENCES user_preferences (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497CCD6FB7 ON user (preferences_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497CCD6FB7');
        $this->addSql('DROP TABLE following');
        $this->addSql('DROP TABLE user_preferences');
        $this->addSql('DROP INDEX UNIQ_8D93D6497CCD6FB7 ON user');
        $this->addSql('ALTER TABLE user DROP preferences_id, DROP roles, DROP confirmation_token, DROP enabled, CHANGE email email VARCHAR(254) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE full_name full_name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
