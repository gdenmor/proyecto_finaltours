<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122124410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE informe (id INT AUTO_INCREMENT NOT NULL, tour_id INT DEFAULT NULL, observaciones VARCHAR(255) DEFAULT NULL, dinero INT NOT NULL, imagen VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7E75E1D915ED8D43 (tour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE valoracion (id INT AUTO_INCREMENT NOT NULL, reserva_id INT DEFAULT NULL, tour_id INT DEFAULT NULL, estrellas INT NOT NULL, comentario VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_6D3DE0F4D67139E8 (reserva_id), INDEX IDX_6D3DE0F415ED8D43 (tour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE informe ADD CONSTRAINT FK_7E75E1D915ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F4D67139E8 FOREIGN KEY (reserva_id) REFERENCES reserva (id)');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F415ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE informe DROP FOREIGN KEY FK_7E75E1D915ED8D43');
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F4D67139E8');
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F415ED8D43');
        $this->addSql('DROP TABLE informe');
        $this->addSql('DROP TABLE valoracion');
    }
}
