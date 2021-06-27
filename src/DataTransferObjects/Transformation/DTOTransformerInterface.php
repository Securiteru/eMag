<?php


namespace App\DataTransferObjects\Transformation;


interface DTOTransformerInterface
{
    public function transformFromEntity($object);
    public function transformCollection(iterable $objects): iterable;
}