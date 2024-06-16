<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240616060946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create coupon table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE public.coupon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE public.coupon (
                id INT NOT NULL, 
                code VARCHAR(255) NOT NULL,
                type VARCHAR(255) NOT NULL,
                value DOUBLE PRECISION NOT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2E514B2277153098 ON public.coupon (code)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE public.coupon_id_seq CASCADE');
        $this->addSql('DROP TABLE public.coupon');
    }
}
