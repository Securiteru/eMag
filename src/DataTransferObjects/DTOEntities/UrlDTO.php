<?php


namespace App\DataTransferObjects\DTOEntities;

use JMS\Serializer\Annotation as Serialization;

class UrlDTO
{
    /**
     * @Serialization\Type("string")
     */
    public string $link_type;

    /**
     * @Serialization\Type("string")
     */
    public string $link;
}