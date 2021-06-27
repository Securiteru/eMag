<?php


namespace App\DataTransferObjects\Transformation;


abstract class AbstractDtoTransformer implements DTOTransformerInterface
{
    /**
     * Transforms an entire collection of items by using the class' own transformFromEntity function.
     *
     * @param iterable $objects
     * @return array
     */
    public function transformCollection(iterable $objects): array
    {
        $dtoEntities=array();
        foreach ($objects as $object) {
            $dtoEntities[] = $this->transformFromEntity($object);
        }
        return $dtoEntities;
    }
}