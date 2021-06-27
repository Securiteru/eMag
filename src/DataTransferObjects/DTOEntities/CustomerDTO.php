<?php


namespace App\DataTransferObjects\DTOEntities;


use JMS\Serializer\Annotation as Serialization;

class CustomerDTO
{

    /**
     * @Serialization\Type("string")
     */
    public string $email;

    /**
     * @Serialization\Type("string")
     */
    public string $journey_hash;

}