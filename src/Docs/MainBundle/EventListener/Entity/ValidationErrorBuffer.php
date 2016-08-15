<?php
namespace Docs\MainBundle\EventListener\Entity;

use Docs\CommonBundle\Doctrine\AbstractEntity;

class ValidationErrorBuffer
{
    /**
     * Array of entities
     * with validation violations
     */
    protected $entities;

    /**
     * Fields and their errors
     * @var unknown
     */
    protected $fieldErrors;

    /**
     * Errors without fields
     * @var Array
     */
    protected $errors;
    /**
     * @param AbstractEntity $entity
     * @return ValidationErrorBuffer
     */
    public function setEntity(AbstractEntity $entity)
    {
        $this->entities[] = $entity;
        return $this;
    }

    /**
     * Return all etities that have
     * validation violations
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * Set error message to concrete field
     * @param string $field
     * @param string $msg
     * @return ValidationErrorBuffer
     */
    public function setFieldError($field, $msg)
    {
        $this->fieldErrors[] = [$field=>$msg];
        return $this;
    }

    /**
     * Get concrete field error msg
     * @param unknown $fieldName
     */
    public function getFieldError($fieldName)
    {
        if (array_key_exists($fieldName, $this->fieldErrors)) {
            return $this->fieldErrors[$fieldName];
        }
    }

    /**
     * Get all fields with
     * validation violations
     */
    public function getAllFieldErrors()
    {
        return $this->fieldErrors;
    }

    /**
     * return count of fields with
     * validation violations
     * [for checking purposes]
     * @return number
     */
    public function getErrorCount()
    {
        return count($this->fieldErrors);
    }

    /**
     * @param string $msg
     * @return \Docs\MainBundle\EventListener\Entity\ValidationErrorBuffer
     */
    public function setErrorWithoutField($msg)
    {
        $this->errors[] = $msg;
        return $this;
    }

    /**
     * return errors that has no fields
     */
    public function getErrorsWithoutFields()
    {
        return $this->errors;
    }
}
