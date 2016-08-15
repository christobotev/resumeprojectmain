<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160427221831 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("
            INSERT INTO `Users` (`userID`, `username`, `password`, `email`, `salt`, `is_active`, `googleID`) VALUES
            (NULL , 'dummyDoc1', '$2y$10$wKQ5Xe5yHT2OmqqEqYHui.oINYAnMaoO2BCSkdBIEjpCdbFhlSkLS', 'dummy@email.com', 'e271390a9e497368fb3e1aea775b8292', 1, '0'),
            (NULL , 'dummyDoc2', '$2y$10$yMmsT8j4ITUOrKt48sECEuOj80gVaJNhe.sZZ6D5.fQGXpTM.NWrm', 'dummy2@email.com', '33d78dd73a1ae69e3eb6bb77212e132a', 1, '0'),
            (NULL , 'dummyDoc3', '$2y$10$/p37CiI1Mk4z.0pjTZbrCufbBUdlf.83aKBqcWPcj2d/w9f.cGZle', 'dummy3@email.com', '48eec844f119889db406d85c24607f8b', 1, '0'),
            (NULL , 'dummyDoc4', '$2y$10$xsXeB/msKajS6RHW6Vbr0.5PJOwtrpBTfD2ncRCTBEG2og1u8tM5C', 'dummy4@email.com', '54d6b71ef8a9986b3825eac28c3de8b1', 1, '0');
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->skipIf(true,"No down migration");
    }
}
