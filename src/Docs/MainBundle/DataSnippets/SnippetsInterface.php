<?php

namespace Docs\MainBundle\DataSnippets;

/**
 * Interface that must be implemented by the data snippet classes
 *
 * Snippets can be used in many different templates/modal windows, dispite of context
 * @author h.botev
 */
interface SnippetsInterface
{
    /**
     * Return the template name for the snippet
     * @return string
     */
    public function getSnippetTemplate();

    /**
     * Build the snippet data
     * @param mixed $resourceID
     */
    public function buildSnippetData($resourceID);

    /**
     * Return the built snippet data
     * @return arrray
     */
    public function getData();
}
