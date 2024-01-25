<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240122194454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TYPE status_enum AS ENUM (\'queued\', \'processing\', \'completed\', \'error\')');
        $this->addSql(
            'CREATE TABLE translations (
				id UUID NOT NULL,
				source_lang VARCHAR(5) DEFAULT NULL,
				original_text VARCHAR(255) NOT NULL,
				target_lang VARCHAR(5) NOT NULL,
				status status_enum DEFAULT \'queued\' NOT NULL,
				translated_text VARCHAR(255) DEFAULT NULL,
				created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
				updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
				PRIMARY KEY(id)
			)'
        );
        $this->addSql('COMMENT ON COLUMN translations.status IS \'(DC2Type:status_enum)\'');
        $this->addSql('COMMENT ON COLUMN translations.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN translations.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE translations');
    }
}
