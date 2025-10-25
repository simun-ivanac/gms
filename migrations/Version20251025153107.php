<?php

/**
 * Migration Version20251025153107.
 */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20251025153107.
 */
final class Version20251025153107 extends AbstractMigration
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
		$this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, currency VARCHAR(255) NOT NULL, repeatability VARCHAR(255) NOT NULL, num_of_sessions_per_week INT DEFAULT NULL, payment_frequency_in_days INT DEFAULT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE `member` (id INT AUTO_INCREMENT NOT NULL, photo VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, date_of_birth DATE NOT NULL, gender VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, pin BIGINT NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE plan (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, price INT NOT NULL, currency VARCHAR(255) NOT NULL, duration_in_days INT NOT NULL, are_visitations_limited TINYINT(1) NOT NULL, num_of_visitations_per_week INT DEFAULT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, team_member_role VARCHAR(255) NOT NULL, permissions JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE team_member (id INT AUTO_INCREMENT NOT NULL, photo VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, date_of_birth DATE NOT NULL, gender VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, phone_number VARCHAR(255) NOT NULL, pin BIGINT NOT NULL, is_active TINYINT(1) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
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
		$this->addSql('DROP TABLE `group`');
		$this->addSql('DROP TABLE `member`');
		$this->addSql('DROP TABLE plan');
		$this->addSql('DROP TABLE role');
		$this->addSql('DROP TABLE team_member');
		$this->addSql('DROP TABLE messenger_messages');
	}
}
