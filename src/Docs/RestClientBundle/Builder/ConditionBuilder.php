<?php
namespace Docs\RestClientBundle\Builder;

use Docs\RestClientBundle\Exception\ConditionBuilderException as BuilderException;

/**
 * Request query parameters builder
 * 
 * @author h.botev
 *        
 */
class ConditionBuilder implements ConditionBuilderInterface
{

    /**
     * Stores all query parameters
     * 
     * @var array
     */
    protected $conditions = [];

    /**
     * Add condition to the "addition" section of the query params
     * 
     * @param array $condition            
     * @throws \BuilderException
     * @return \Docs\RestClientBundle\Builder\ConditionBuilder
     */
    public function addCondition(array $condition)
    {
        if (empty($condition['field']) || ! isset($condition['value'])) {
            throw new BuilderException("A valid condition must contain at least a field name and a value for it");
        }
        
        $this->conditions['addition']['conditions'][] = $condition;
        
        return $this;
    }

    /**
     * Returns all condition parameters
     * 
     * @return array
     */
    public function getQueryParams()
    {
        return $this->conditions;
    }

    /**
     * Add order parameters to the query
     * 
     * @param array $orderOptions            
     * @throws BuilderException
     * @return \Docs\RestClientBundle\Builder\ConditionBuilder
     */
    public function addOrder(array $orderOptions)
    {
        if (empty($orderOptions['field'])) {
            throw new BuilderException("A valid order config must contain at leasta  field name");
        }
        
        if (empty($orderOptions['direction'])) {
            $orderOptions['direction'] = "ASC";
        }
        
        $this->conditions['addition']['order'][] = $orderOptions;
        
        return $this;
    }

    /**
     * Add query parameter
     * 
     * @param string $name            
     * @param mixed $value            
     * @return \Docs\RestClientBundle\Builder\ConditionBuilder
     */
    public function addQueryParameter($name, $value)
    {
        $this->conditions[$name] = $value;
        return $this;
    }

    /**
     * Reset the query parameters.
     * If $conditionNames is empty,
     * reset all parameters, otherwise reset only the parameters
     * with names from the $conditionNames list
     * 
     * @param array $conditionNames            
     * @return \Docs\RestClientBundle\Builder\ConditionBuilder
     */
    public function resetQuery(array $conditionNames = [])
    {
        if (empty($conditionNames)) {
            $this->conditions = [];
            return $this;
        }
        
        foreach ($conditionNames as $name) {
            unset($this->conditions[$name]);
        }
        
        return $this;
    }
}
