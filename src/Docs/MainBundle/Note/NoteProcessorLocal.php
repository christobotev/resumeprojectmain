<?php
namespace Docs\MainBundle\Note;

use Docs\CommonBundle\Entity\Note;
use Docs\MainBundle\Persistence\Persister;
use Docs\CommonBundle\Entity\User;

class NoteProcessorLocal
{
    /**
     * @var Persister
     */
    protected $persister;

    /**
     * @param Persister $persister
     */
    public function __construct($persister)
    {
        $this->persister = $persister;
    }

    /**
     * @param string $noteStr
     * @param User $user
     * @return \Docs\CommonBundle\Entity\Note
     */
    public function createNote($noteStr, User $user)
    {
        $note = new Note();
        $note->setContent($noteStr);
        $note->setUser($user);
        $note->setCreated(new \DateTime());

        $this->persister->persist($note);
        return $note;
    }
}
