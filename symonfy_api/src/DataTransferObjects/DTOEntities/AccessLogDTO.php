<?php


namespace App\DataTransferObjects\DTOEntities;


use JMS\Serializer\Annotation as Serialization;

class AccessLogDTO
{
    /**
     * @Serialization\Type("int")
     */
    public int $customer_id;

    /**
     * @Serialization\Type("int")
     */
    public int $url_id;

    /**
     * @Serialization\Type("string")
     */
    public string $timestamp;

    /**
     * @Serialization\Type("string")
     */
    public string $url_type;

    /**
     * @Serialization\Type("string")
     */
    public string $url;


}