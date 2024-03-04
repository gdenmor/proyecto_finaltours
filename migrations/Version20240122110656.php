<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122110656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tour (id INT AUTO_INCREMENT NOT NULL, ruta_id INT NOT NULL, guia_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, fecha_inicio DATETIME NOT NULL, fecha_fin DATETIME NOT NULL, INDEX IDX_6AD1F969ABBC4845 (ruta_id), INDEX IDX_6AD1F96962AA81F (guia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tour ADD CONSTRAINT FK_6AD1F969ABBC4845 FOREIGN KEY (ruta_id) REFERENCES ruta (id)');
        $this->addSql('ALTER TABLE tour ADD CONSTRAINT FK_6AD1F96962AA81F FOREIGN KEY (guia_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reserva ADD tour_id INT NOT NULL');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B15ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
        $this->addSql('CREATE INDEX IDX_188D2E3B15ED8D43 ON reserva (tour_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B15ED8D43');
        $this->addSql('ALTER TABLE tour DROP FOREIGN KEY FK_6AD1F969ABBC4845');
        $this->addSql('ALTER TABLE tour DROP FOREIGN KEY FK_6AD1F96962AA81F');
        $this->addSql('DROP TABLE tour');
        $this->addSql('DROP INDEX IDX_188D2E3B15ED8D43 ON reserva');
        $this->addSql('ALTER TABLE reserva DROP tour_id');
    }
}
