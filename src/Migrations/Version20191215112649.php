<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191215112649 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders DROP INDEX UNIQ_E52FFDEE5AA1164F, ADD INDEX IDX_E52FFDEE5AA1164F (payment_method_id)');
        $this->addSql('ALTER TABLE orders CHANGE payment_method_id payment_method_id INT NOT NULL');
        $this->addSql('ALTER TABLE products_order CHANGE orders_id orders_id INT NOT NULL, CHANGE product_id product_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders DROP INDEX IDX_E52FFDEE5AA1164F, ADD UNIQUE INDEX UNIQ_E52FFDEE5AA1164F (payment_method_id)');
        $this->addSql('ALTER TABLE orders CHANGE payment_method_id payment_method_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products_order CHANGE orders_id orders_id INT DEFAULT NULL, CHANGE product_id product_id INT DEFAULT NULL');
    }
}
