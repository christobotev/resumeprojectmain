<?php
namespace Docs\MainBundle\Twig\Extension;

/**
 * Assign stars according to user rating
 * @author h.botev
 *
 */
class AssignRating extends \Twig_Extension
{
    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("assignStars", [$this, "calculateStars"])
        ];
    }

    /**
     * round the rating according to 5 stars max
     * @param string $route
     * @return boolean
     */
    public function calculateStars($rating)
    {
        return round($rating/2);
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return "assignRating";
    }
}
