<?php
namespace Docs\RestClientBundle\Builder;

/**
 * Interface implemented by the condition builder classes
 * 
 * @author h.botev
 *        
 */
interface ConditionBuilderInterface
{

    /**
     * Add condition to the "addition" section of the query params
     * 
     * @param array $condition            
     * @throws \BuilderException
     * @return \Docs\RestClientBundle\Builder\ConditionBuilderInterface
     */
    public function addCondition(array $condition);

    /**
     * Returns all condition parameters
     * 
     * @return array
     */
    public function getQueryParams();

    /**
     * Add order parameters to the query
     * 
     * @param array $orderOptions            
     * @throws BuilderException
     * @return \Docs\RestClientBundle\Builder\ConditionBuilderInterface
     */
    public function addOrder(array $orderOptions);

    /**
     * Add query parameter
     * 
     * @param string $name            
     * @param mixed $value            
     * @return \Docs\RestClientBundle\Builder\ConditionBuilderInterface
     */
    public function addQueryParameter($name, $value);

    /**
     * Reset the query parameters.
     * If $conditionNames is empty,
     * reset all parameters, otherwise reset only the parameters
     * with names from the $conditionNames list
     * 
     * @param array $conditionNames            
     * @return \Docs\RestClientBundle\Builder\ConditionBuilderInterface
     */
    public function resetQuery(array $conditionNames = []);
}
