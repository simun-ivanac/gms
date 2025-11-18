<?php

/**
 * Migration Version20251118165113.
 */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20251118165113.
 */
final class Version20251118165113 extends AbstractMigration
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
		$this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES team_member (id)');
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
		$this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
		$this->addSql('DROP TABLE reset_password_request');
		// phpcs:enable
	}
}
