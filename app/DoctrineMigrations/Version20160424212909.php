<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160424212909 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE `Reminder` (
             `reminderID` int(11) unsigned NOT NULL AUTO_INCREMENT,
             `userID` int(11) NOT NULL,
             `noteID` int(11) NOT NULL,
             `scheduled` datetime NOT NULL,
             `status` tinyint(3) NOT NULL,
             `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
             `createdByUserID` int(11) NOT NULL,
             PRIMARY KEY (`reminderID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->skipIf(true, "No down migration");
    }
}
