<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231017152037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085643256915B');
        $this->addSql('DROP INDEX IDX_5A1085643256915B ON vote');
        $this->addSql('ALTER TABLE vote CHANGE relation_id joueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('CREATE INDEX IDX_5A108564A9E2D76C ON vote (joueur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564A9E2D76C');
        $this->addSql('DROP INDEX IDX_5A108564A9E2D76C ON vote');
        $this->addSql('ALTER TABLE vote CHANGE joueur_id relation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085643256915B FOREIGN KEY (relation_id) REFERENCES joueur (id)');
        $this->addSql('CREATE INDEX IDX_5A1085643256915B ON vote (relation_id)');
    }
}
