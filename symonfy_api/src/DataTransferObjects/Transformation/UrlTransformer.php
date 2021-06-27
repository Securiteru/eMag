<?php


namespace App\DataTransferObjects\Transformation;

use App\DataTransferObjects\DTOEntities\UrlDTO;
use App\Entity\Url;

class UrlTransformer extends AbstractDtoTransformer
{
    /**
     * @param Url $object
     * @return UrlDTO
     */
    public function transformFromEntity($object): UrlDTO
    {
        $dto = new UrlDTO();
        $dto->link = $object->getLink();
        $dto->link_type=$object->getLinkType();

        return $dto;
    }
}