<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191020113737 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE products_order_product');
        $this->addSql('ALTER TABLE products_order DROP INDEX UNIQ_15706D48CFFE9AD6, ADD INDEX IDX_15706D48CFFE9AD6 (orders_id)');
        $this->addSql('ALTER TABLE products_order ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products_order ADD CONSTRAINT FK_15706D484584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_15706D484584665A ON products_order (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE products_order_product (products_order_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_1439049E989E7D8B (products_order_id), INDEX IDX_1439049E4584665A (product_id), PRIMARY KEY(products_order_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE products_order_product ADD CONSTRAINT FK_1439049E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_order_product ADD CONSTRAINT FK_1439049E989E7D8B FOREIGN KEY (products_order_id) REFERENCES products_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_order DROP INDEX IDX_15706D48CFFE9AD6, ADD UNIQUE INDEX UNIQ_15706D48CFFE9AD6 (orders_id)');
        $this->addSql('ALTER TABLE products_order DROP FOREIGN KEY FK_15706D484584665A');
        $this->addSql('DROP INDEX IDX_15706D484584665A ON products_order');
        $this->addSql('ALTER TABLE products_order DROP product_id');
    }
}
