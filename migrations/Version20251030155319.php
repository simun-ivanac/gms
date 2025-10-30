<?php

/**
 * Migration Version20251030155319.
 */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20251030155319.
 */
final class Version20251030155319 extends AbstractMigration
{
	/**
	 * Get the description of the migration.
	 *
	 * @return string
	 */
	public function getDescription(): string
	{
		return '';
	}

	/**
	 * Run the migrations.
	 *
	 * @param Schema $schema Schema.
	 *
	 * @return void
	 */
	// phpcs:ignore
	public function up(Schema $schema): void
	{
		// phpcs:disable
		$this->addSql('ALTER TABLE `member` CHANGE photo photo_filename VARCHAR(255) DEFAULT NULL');
		// phpcs:enable
	}

	/**
	 * Reverse the migrations.
	 *
	 * @param Schema $schema Schema.
	 *
	 * @return void
	 */
	// phpcs:ignore
	public function down(Schema $schema): void
	{
		// phpcs:disable
		$this->addSql('ALTER TABLE `member` CHANGE photo_filename photo VARCHAR(255) DEFAULT NULL');
		// phpcs:enable
	}
}
