<?php


namespace App\DataTransferObjects\Transformation;


use App\DataTransferObjects\DTOEntities\AccessLogDTO;
use App\DataTransferObjects\DTOEntities\CustomerDTO;
use App\Entity\AccessLog;
use App\Entity\Customer;

class AccessLogTransformer extends AbstractDtoTransformer
{
    /**
     * @param AccessLog $object
     *
     * @return AccessLogDTO
     */
    public function transformFromEntity($object): AccessLogDTO
    {
        $dto = new AccessLogDTO();
        if($object->getCustomer()){
            $dto->customer_id = $object->getCustomer()->getId();
        }
        if($object->getUrl()) {
            $dto->url_id = $object->getUrl()->getId();
            $dto->url_type = $object->getUrl()->getLinkType();
            $dto->url = $object->getUrl()->getLink();
        }
        $dto->timestamp=$object->getTimestamp()->format('Y-m-d H:i:s');

        return $dto;
    }
}