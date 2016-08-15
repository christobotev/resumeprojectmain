<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160425202346 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // don't give them rights to 'pick' a doctor
        $resources = [
            'Docs_MainBundle_Controller_RemindersController_listAction',
            'Docs_MainBundle_Controller_DefaultController_indexAction',
            'Docs_MainBundle_Controller_AppointmentsController_listOpenAction',
            'Docs_MainBundle_Controller_AppointmentsController_listClosedAction',
            'Docs_MainBundle_Controller_UsersController_listAllAction'
        ];

        $roleResource = $this->connection->executeQuery("
                    SELECT
                        roleID
                    FROM
                        Roles
                    WHERE
                        name = 'ROLE_DOC'
            ");

        $roleResource = $roleResource->fetch(\PDO::FETCH_ASSOC);

        foreach ($resources as $resource) {
            $resourceRow = $this->connection->executeQuery("
                    SELECT
                        resourceID
                    FROM
                        Resources
                    WHERE
                        name = '{$resource}'
            ");

            $resourceResult = $resourceRow->fetch(\PDO::FETCH_ASSOC);

            $this->connection->insert(
                'RoleResources',
                ['rights' => NULL,
                    'roleID' => $roleResource['roleID'],
                    'resourceID' => $resourceResult['resourceID']
                ]
            );
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->skipIf(true, "no down migration");
    }
}
