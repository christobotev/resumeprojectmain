<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160501210407 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE  `Users` ADD  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");

        $this->addSql("
            ALTER TABLE
                `Users`
            ADD `firstName` VARCHAR( 45 ) NULL AFTER  `userID` ,
            ADD `lastName` VARCHAR( 45 ) NULL AFTER  `firstName`
        ");

        $this->addSql("ALTER TABLE  `Users` ADD  `rating` DECIMAL( 2.2 ) NULL");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->skipIf(true, "no down migration");
    }
}
