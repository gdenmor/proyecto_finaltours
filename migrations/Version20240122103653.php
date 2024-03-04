<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122103653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, foto VARCHAR(255) NOT NULL, geolocalizacion VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_ruta (item_id INT NOT NULL, ruta_id INT NOT NULL, INDEX IDX_B9EB477126F525E (item_id), INDEX IDX_B9EB477ABBC4845 (ruta_id), PRIMARY KEY(item_id, ruta_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_ruta ADD CONSTRAINT FK_B9EB477126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_ruta ADD CONSTRAINT FK_B9EB477ABBC4845 FOREIGN KEY (ruta_id) REFERENCES ruta (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_ruta DROP FOREIGN KEY FK_B9EB477126F525E');
        $this->addSql('ALTER TABLE item_ruta DROP FOREIGN KEY FK_B9EB477ABBC4845');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_ruta');
    }
}
