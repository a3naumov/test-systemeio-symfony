<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240616061354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create product table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE public.product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE public.product (
                id INT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                price DOUBLE PRECISION NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX name_idx ON public.product (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE public.product_id_seq CASCADE');
        $this->addSql('DROP TABLE public.product');
    }
}
