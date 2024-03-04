<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123182049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE horario (id INT AUTO_INCREMENT NOT NULL, dia VARCHAR(255) NOT NULL, hora_inicio TIME NOT NULL, hora_fin TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE horario_ruta (horario_id INT NOT NULL, ruta_id INT NOT NULL, INDEX IDX_7E532BFB4959F1BA (horario_id), INDEX IDX_7E532BFBABBC4845 (ruta_id), PRIMARY KEY(horario_id, ruta_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE horario_ruta ADD CONSTRAINT FK_7E532BFB4959F1BA FOREIGN KEY (horario_id) REFERENCES horario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE horario_ruta ADD CONSTRAINT FK_7E532BFBABBC4845 FOREIGN KEY (ruta_id) REFERENCES ruta (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE horario_ruta DROP FOREIGN KEY FK_7E532BFB4959F1BA');
        $this->addSql('ALTER TABLE horario_ruta DROP FOREIGN KEY FK_7E532BFBABBC4845');
        $this->addSql('DROP TABLE horario');
        $this->addSql('DROP TABLE horario_ruta');
    }
}
