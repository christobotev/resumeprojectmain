<?php
namespace Docs\MainBundle\EventListener\Entity\Exception;

use Docs\MainBundle\EventListener\Entity\ValidationErrorBuffer;

/**
 * Description of Validation
 */
class ValidationException extends \Exception
{
    /**
     * @var \Docs\MainBundle\EventListener\Entity\Exception\ValidationException
     */
    protected $errorBuffer;

    /**
     * @param ValidationErrorBuffer $errorBuffer
     * @return \Docs\MainBundle\EventListener\Entity\Exception\ValidationException
     */
    public function setErrorBuffer(ValidationErrorBuffer $errorBuffer)
    {
        $this->errorBuffer = $errorBuffer;
        return $this;
    }

    /**
     * @return \Docs\MainBundle\EventListener\Entity\Exception\ValidationException
     */
    public function getErrorBuffer()
    {
        return $this->errorBuffer;
    }
}
