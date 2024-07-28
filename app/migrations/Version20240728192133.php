<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240728192133 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE game_info (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, couple VARCHAR(255) NOT NULL, score VARCHAR(255) NOT NULL, winner VARCHAR(255) NOT NULL, INDEX IDX_209C5D7B33D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, winner VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_info ADD CONSTRAINT FK_209C5D7B33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE game_info DROP FOREIGN KEY FK_209C5D7B33D1A3E7');
        $this->addSql('DROP TABLE game_info');
        $this->addSql('DROP TABLE tournament');
    }
}
