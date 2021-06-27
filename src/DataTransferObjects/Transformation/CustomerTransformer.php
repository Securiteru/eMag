<?php


namespace App\DataTransferObjects\Transformation;


use App\DataTransferObjects\DTOEntities\CustomerDTO;
use App\DataTransferObjects\Transformation\AbstractDtoTransformer;
use App\Entity\Customer;

class CustomerTransformer extends AbstractDtoTransformer
{
    /**
     * @param Customer $object
     *
     * @return CustomerDTO
     */
    public function transformFromEntity($object): CustomerDTO
    {
        $dto = new CustomerDTO();
        $dto->email = $object->getEmail();
        $dto->journey_hash=$object->getCustomerJourneyHash();

        return $dto;
    }
}