<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181007135447 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE job (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', service_id INT NOT NULL, zipcode_id CHAR(5) NOT NULL, title VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, date_to_be_done DATE NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB; ALTER TABLE job ADD FOREIGN KEY (service_id) REFERENCES service(id); ALTER TABLE job ADD FOREIGN KEY (zipcode_id) REFERENCES zipcode(id);');
        $this->addSql('ALTER TABLE job ADD FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE job ADD FOREIGN KEY (zipcode_id) REFERENCES zipcode (id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE job');
    }
}
