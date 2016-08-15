<?php
namespace Docs\RestClientBundle\Serializer;

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

/**
 * Interface that must be implemented by the response serializers
 *
 * @author h.botev
 *
 */
interface ResponseSerializerInterface extends DecoderInterface, EncoderInterface
{
}
