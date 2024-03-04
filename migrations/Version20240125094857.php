<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240125094857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ruta DROP fecha_inicio, DROP fecha_fin');
        $this->addSql('ALTER TABLE tour ADD fecha_inicio DATETIME NOT NULL, ADD fecha_fin DATETIME NOT NULL, ADD horario JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ruta ADD fecha_inicio DATETIME NOT NULL, ADD fecha_fin DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tour DROP fecha_inicio, DROP fecha_fin, DROP horario');
    }
}
