<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160429173328 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE `Appointments` (
             `appointmentID` int(11) unsigned NOT NULL AUTO_INCREMENT,
             `status` tinyint(3) NOT NULL DEFAULT '0',
             `userID` int(11) NOT NULL,
             `withUserID` int(11) NOT NULL,
             `scheduled` datetime NOT NULL,
             `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
             `noteID` INT( 11 ) UNSIGNED NULL,
             PRIMARY KEY (`appointmentID`),
             KEY `Note` (`noteID`),
             KEY `User` (`userID`),
             KEY `withUser` (`withUserID`),
             CONSTRAINT `fk_appointments_note` FOREIGN KEY (`noteID`) REFERENCES `Note` (`noteID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
             CONSTRAINT `fk_appointments_user1` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
             CONSTRAINT `fk_appointments_user2` FOREIGN KEY (`withUserID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->skipIf(true, 'no down migration');
    }
}
