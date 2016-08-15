<?php
namespace Docs\MainBundle\Twig\Extension;

/**
 * Since we can't override localizeddate
 * here's an extension to check wether or not
 * the string should be localized
 * @author h.botev
 *
 */
class LocalizeDateExtension extends \Twig_Extension
{
    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFilters()
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'localizedate',
                function (\Twig_Environment $env, $date, $dateFormat = 'medium', $timeFormat = 'medium') {
                    return $this->localizeDateFilter($env, $date, $dateFormat, $timeFormat);
                },
                ['needs_environment' => true]
            )
        );
    }

    /**
     * If date is missing return empty string or n/a
     * otherwise use localizeddate filter
     *
     * Depending on the timezone set in php.ini, in some cases
     * '0000-00-00 00:00:00' can turn into a negative timestamp
     * and we'd rather show 'n/a'
     *
     * @param \Twig_Environment $env
     * @param string $date
     * @param string $dateFormat
     * @param string $timeFormat
     * @return string
     */
    public function localizeDateFilter(\Twig_Environment $env, $date, $dateFormat = 'medium', $timeFormat = 'medium')
    {

        if ($date->getTimestamp() < 0) {
            return "N/A";
        } elseif (!empty($date)) {
            $localizeddate = $env->getFilter('localizeddate');
            $callable = $localizeddate->getCallable();

            return $callable($env, $date, $dateFormat, $timeFormat);
        }

        return '';
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'localizeDateFilter';
    }
}
