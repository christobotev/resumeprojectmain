<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160425111214 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->connection->insert('Resources', array('resourceID' => NULL,'name' =>'Docs_MainBundle_Controller_UsersController_listAvailableAction'));

        $resource = $this->connection->executeQuery("
                SELECT
                    resourceID
                FROM
                    Resources
                WHERE
                    name = 'Docs_MainBundle_Controller_UsersController_listAvailableAction'
                ");

        $resource = $resource->fetch(\PDO::FETCH_ASSOC);

        // 1 - Admin
        $this->connection->insert(
                'RoleResources',
                ['rights' => NULL,
                         'roleID' =>'1',
                         'resourceID' => $resource['resourceID']
                ]
        );

        // 2 - User
        $this->connection->insert(
                'RoleResources',
                 ['rights' => NULL,
                         'roleID' =>'2',
                         'resourceID' => $resource['resourceID']
                 ]
        );

        // 3 - Google User
        $this->connection->insert(
            'RoleResources',
            ['rights' => NULL,
                'roleID' =>'3',
                'resourceID' => $resource['resourceID']
            ]
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
                    name = 'Docs_MainBundle_Controller_UsersController_listAvailableAction'
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
