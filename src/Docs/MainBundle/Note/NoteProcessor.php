<?php
namespace Docs\MainBundle\Note;

use Docs\MainBundle\RestClient\DocsNote;
use Docs\CommonBundle\Entity\User;

class NoteProcessor
{
    protected $noteClient;

    public function __construct(DocsNote $noteClient)
    {
        $this->noteClient = $noteClient;
    }

    public function process($noteStr, User $user)
    {
        $today = new \DateTime();
        $note = [ 'Note' => [
                'content' => $noteStr,
                'user' => $user->getUserID(),
                'created' => $today->format('Y-m-d')
            ]
        ];

        return $this->noteClient->create($note)
                                    ->getData();
    }
}
