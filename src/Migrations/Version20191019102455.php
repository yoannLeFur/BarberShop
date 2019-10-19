<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191019102455 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE products_order (id INT AUTO_INCREMENT NOT NULL, orders_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_15706D48CFFE9AD6 (orders_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products_order_product (products_order_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_1439049E989E7D8B (products_order_id), INDEX IDX_1439049E4584665A (product_id), PRIMARY KEY(products_order_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, roles_id INT DEFAULT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, INDEX IDX_1483A5E938C751C4 (roles_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE products_order ADD CONSTRAINT FK_15706D48CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE products_order_product ADD CONSTRAINT FK_1439049E989E7D8B FOREIGN KEY (products_order_id) REFERENCES products_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_order_product ADD CONSTRAINT FK_1439049E4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E938C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id)');
        $this->addSql('ALTER TABLE forgot_password ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forgot_password ADD CONSTRAINT FK_2AB9B566A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_2AB9B566A76ED395 ON forgot_password (user_id)');
        $this->addSql('ALTER TABLE orders ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEEA76ED395 ON orders (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE products_order_product DROP FOREIGN KEY FK_1439049E989E7D8B');
        $this->addSql('ALTER TABLE forgot_password DROP FOREIGN KEY FK_2AB9B566A76ED395');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('DROP TABLE products_order');
        $this->addSql('DROP TABLE products_order_product');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP INDEX IDX_2AB9B566A76ED395 ON forgot_password');
        $this->addSql('ALTER TABLE forgot_password DROP user_id');
        $this->addSql('DROP INDEX IDX_E52FFDEEA76ED395 ON orders');
        $this->addSql('ALTER TABLE orders DROP user_id');
    }
}
