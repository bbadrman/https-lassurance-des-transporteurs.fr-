<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260403162645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transport CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE prenom prenom VARCHAR(100) DEFAULT NULL, CHANGE raison raison VARCHAR(150) DEFAULT NULL, CHANGE activite activite VARCHAR(50) DEFAULT NULL, CHANGE assurer assurer VARCHAR(50) DEFAULT NULL, CHANGE type type VARCHAR(50) DEFAULT NULL, CHANGE souh_assurer souh_assurer VARCHAR(100) DEFAULT NULL, CHANGE email email VARCHAR(180) DEFAULT NULL, CHANGE tele tele VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transport CHANGE nom nom VARCHAR(15) DEFAULT NULL, CHANGE prenom prenom VARCHAR(15) DEFAULT NULL, CHANGE raison raison VARCHAR(10) DEFAULT NULL, CHANGE activite activite VARCHAR(10) DEFAULT NULL, CHANGE assurer assurer VARCHAR(10) DEFAULT NULL, CHANGE type type VARCHAR(10) DEFAULT NULL, CHANGE souh_assurer souh_assurer VARCHAR(20) DEFAULT NULL, CHANGE email email VARCHAR(15) DEFAULT NULL, CHANGE tele tele VARCHAR(15) DEFAULT NULL');
    }
}
