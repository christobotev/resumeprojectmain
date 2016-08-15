<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160424212908 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE `Note` (
             `noteID` int(11) unsigned NOT NULL AUTO_INCREMENT,
             `content` varchar(500) NOT NULL,
             `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
             `userID` int(11) unsigned NOT NULL,
             PRIMARY KEY (`noteID`)
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
