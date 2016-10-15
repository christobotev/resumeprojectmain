<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161015213759 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->connection->insert('Resources', array('resourceID' => NULL,'name' =>'Docs_MainBundle_Controller_RemindersController_addReminderModalAction'));

        $resource = $this->connection->executeQuery("
                SELECT
                    resourceID
                FROM
                    Resources
                WHERE
                    name = 'Docs_MainBundle_Controller_RemindersController_addReminderModalAction'
                ");

        $resource = $resource->fetch(\PDO::FETCH_ASSOC);

        // 5 - Doctors
        $this->connection->insert(
            'RoleResources',
            array('rights' => NULL,
                'roleID' =>'5',
                'resourceID' => $resource['resourceID']
            )
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $resource = $this->connection->executeQuery("
                SELECT
                    resourceID
                FROM
                    Resources
                WHERE
                    name = 'Docs_MainBundle_Controller_RatingController_saveAction'
                ");

        $resource = $resource->fetch(\PDO::FETCH_ASSOC);

        $roleResources = $this->connection->executeQuery("
                SELECT
                     roleResourceID
                FROM
                    RoleResources
                WHERE
                    resourceID = " . $resource['resourceID'] . "
                ");

        $roleResources = $roleResources->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($roleResources as $rr) {
            $this->connection->delete('RoleResources', $rr);
        }

        $this->connection->delete('Resources',$resource);
    }
}
