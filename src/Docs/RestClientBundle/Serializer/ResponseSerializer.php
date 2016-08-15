<?php
namespace Docs\RestClientBundle\Serializer;

use Symfony\Component\Serializer\Serializer;

/**
 * Serializer for the service response requests.
 * It's a wrapper
 * arround the symfony serializer.
 *
 * @author h.botev
 */
class ResponseSerializer implements ResponseSerializerInterface
{

    /**
     * The internal serializer
     *
     * @var \Symfony\Component\Serializer\Serializer
     */
    protected $serializer;

    /**
     * List of mappings for types
     * (e.g.
     * ['xml' => ['text/xml', 'application/xml']])
     *
     * @var array
     */
    protected $contentTypeAliases = [];

    /**
     * Initailized the object and set the internal serializer
     *
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Add content type alias
     *
     * @param sting $alias
     * @param string $contentType
     * @return \Docs\RestClientBundle\Serializer\ResponseSerializer
     */
    public function addContentTypeAlias($alias, $contentType)
    {
        $this->contentTypeAliases[$alias][] = $contentType;
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Symfony\Component\Serializer\Encoder\DecoderInterface::decode()
     */
    public function decode($data, $format, array $context = array())
    {
        $format = $this->normalizeFormat($format);

        $result = $this->serializer->decode($data, $format, $context);

        // support for ewt json
        if ($format == "json") {
            $result = $result['ewt'];
        }

        return $result;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Symfony\Component\Serializer\Encoder\DecoderInterface::supportsDecoding()
     */
    public function supportsDecoding($format)
    {
        return $this->serializer->supportsDecoding($this->normalizeFormat($format));
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Symfony\Component\Serializer\Encoder\EncoderInterface::encode()
     */
    public function encode($data, $format, array $context = array())
    {
        return $this->serializer->decode($data, $this->normalizeFormat($format), $context);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Symfony\Component\Serializer\Encoder\EncoderInterface::supportsEncoding()
     */
    public function supportsEncoding($format)
    {
        return $this->serializer->supportsEncoding($this->normalizeFormat($format));
    }

    /**
     * Normalize the format
     *
     * @param string $format
     * @return string
     */
    protected function normalizeFormat($format)
    {
        foreach ($this->contentTypeAliases as $handledFormat => $contentTypes) {
            if (array_search($format, $contentTypes) !== false) {
                return $handledFormat;
            }
        }

        return $format;
    }
}
