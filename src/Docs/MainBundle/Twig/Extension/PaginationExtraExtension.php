<?php
namespace Docs\MainBundle\Twig\Extension;

/**
 * Extra functions for the pagination
 * @author h.botev
 *
 */
class PaginationExtraExtension extends \Twig_Extension
{

    const ITEMS_PER_PAGE_TMPL = "MainBundle:Grid:itemsPerPage.html.twig";

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * Set the twig environment instance
     * @param \Twig_Environment $environment
     */
    public function initEnv(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("pagination_extra_items_per_page", [$this, "itemsPerPage"])
        ];
    }

    /**
     * Generate a select with available items count per page
     * @param string $url
     * @param array $availableItems
     * @return string
     */
    public function itemsPerPage(array $availableItems = [10, 20, 50])
    {
        return  $this->environment->render(
            static::ITEMS_PER_PAGE_TMPL,
            ["items" => $availableItems]
        );
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return "pagination_extra";
    }
}
