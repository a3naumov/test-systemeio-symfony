<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240616061411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tax_rate table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE public.tax_rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE public.tax_rate (
                id INT NOT NULL, 
                country_code VARCHAR(2) NOT NULL, 
                rate DOUBLE PRECISION NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_18FEBA8F026BB7C ON public.tax_rate (country_code)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE public.tax_rate_id_seq CASCADE');
        $this->addSql('DROP TABLE public.tax_rate');
    }
}
