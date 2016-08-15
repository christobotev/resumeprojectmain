<?php
namespace Docs\RestClientBundle\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

/**
 * Overwrire some of the methods of the parent class to
 * look like this
 * <?xml version="1.0" encoding="UTF-8"?>
 * <docs>
 * <result>
 * <contract>1</contract>
 * <contract><x>1</x></contract>
 * </result>
 * <status>success</status>
 * </docs>

 * @author h.botev
 */
class DocsXmlEncoder extends XmlEncoder
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function decode($data, $format, array $context = array())
    {
        if ('' === trim($data)) {
            throw new UnexpectedValueException('Invalid XML data, it can not be empty.');
        }

        $internalErrors = libxml_use_internal_errors(true);
        $disableEntities = libxml_disable_entity_loader(true);
        libxml_clear_errors();

        $dom = new \DOMDocument();
        $dom->loadXML($data, LIBXML_NONET | LIBXML_NOBLANKS);

        libxml_use_internal_errors($internalErrors);
        libxml_disable_entity_loader($disableEntities);

        if ($error = libxml_get_last_error()) {
            libxml_clear_errors();

            throw new UnexpectedValueException($error->message);
        }

        foreach ($dom->childNodes as $child) {
            if ($child->nodeType === XML_DOCUMENT_TYPE_NODE) {
                throw new UnexpectedValueException('Document types are not allowed.');
            }
        }

        $rootNode = $dom->firstChild;

        // todo: throw an exception if the root node name is not correctly configured (bc)

        if ($rootNode->hasChildNodes()) {
            $xpath = new \DOMXPath($dom);
            $data = array();
            foreach ($xpath->query('namespace::*', $dom->documentElement) as $nsNode) {
                $data['@' . $nsNode->nodeName] = $nsNode->nodeValue;
            }

            unset($data['@xmlns:xml']);

            if (empty($data)) {
                return $this->parseXml($rootNode);
            }

            return array_merge($data, (array) $this->parseXml($rootNode));
        }

        if (! $rootNode->hasAttributes()) {
            return $rootNode->nodeValue;
        }

        $data = array();

        foreach ($rootNode->attributes as $attrKey => $attr) {
            $data['@' . $attrKey] = $attr->nodeValue;
        }

        $data['#'] = $rootNode->nodeValue;

        return $data;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    private function parseXml(\DOMNode $node)
    {
        $data = $this->parseXmlAttributes($node);

        $value = $this->parseXmlValue($node);

        if (! count($data)) {
            return $value;
        }

        if (! is_array($value)) {
            $data['#'] = $value;

            return $data;
        }

        if (1 === count($value) && key($value)) {
            $data[key($value)] = current($value);

            return $data;
        }

        foreach ($value as $key => $val) {
            $data[$key] = $val;
        }

        return $data;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    private function parseXmlValue(\DOMNode $node)
    {
        if (! $node->hasChildNodes()) {
            return $node->nodeValue;
        }

        if (1 === $node->childNodes->length
            && in_array(
                $node->firstChild->nodeType,
                [XML_TEXT_NODE,
                XML_CDATA_SECTION_NODE]
        )) {
            return $node->firstChild->nodeValue;
        }

        $value = array();

        foreach ($node->childNodes as $subnode) {
            $val = $this->parseXml($subnode);

            if ('item' === $subnode->nodeName && isset($val['@key'])) {
                if (isset($val['#'])) {
                    $value[$val['@key']] = $val['#'];
                } else {
                    $value[$val['@key']] = $val;
                }
            } else {
                if (isset($value[$subnode->nodeName])) {
                    $value[$subnode->nodeName . "_" . uniqID()][] = $val;
                } else {
                    $value[$subnode->nodeName][] = $val;
                }
            }
        }

        foreach ($value as $key => $val) {
            if (is_array($val) && 1 === count($val)) {
                $value[$key] = current($val);
            }
        }

        return $value;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    private function parseXmlAttributes(\DOMNode $node)
    {
        if (! $node->hasAttributes()) {
            return array();
        }

        $data = array();

        foreach ($node->attributes as $attr) {
            if (ctype_digit($attr->nodeValue)) {
                $data['@' . $attr->nodeName] = (int) $attr->nodeValue;
            } else {
                $data['@' . $attr->nodeName] = $attr->nodeValue;
            }
        }

        return $data;
    }
}
