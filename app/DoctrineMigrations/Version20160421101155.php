<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160421101155 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TABLE `Users` ADD  `googleID` VARCHAR( 25 ) NOT NULL DEFAULT  '0' AFTER  `is_active`");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
       $this->skipIf(true, "No down migration");
    }
}
