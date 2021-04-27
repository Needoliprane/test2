<?php

namespace Hydrators;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Hydrator\HydratorException;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\Query\Query;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class AppDocumentRateLimiterHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate(object $document, array $data, array $hints = array()): array
    {
        $hydratedData = array();

        /** @Field(type="id") */
        if (isset($data['_id']) || (! empty($this->class->fieldMappings['id']['nullable']) && array_key_exists('_id', $data))) {
            $value = $data['_id'];
            if ($value !== null) {
                $typeIdentifier = $this->class->fieldMappings['id']['type'];
                $return = $value instanceof \MongoDB\BSON\ObjectId ? (string) $value : $value;
            } else {
                $return = null;
            }
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['ip']) || (! empty($this->class->fieldMappings['ip']['nullable']) && array_key_exists('ip', $data))) {
            $value = $data['ip'];
            if ($value !== null) {
                $typeIdentifier = $this->class->fieldMappings['ip']['type'];
                $return = (string) $value;
            } else {
                $return = null;
            }
            $this->class->reflFields['ip']->setValue($document, $return);
            $hydratedData['ip'] = $return;
        }

        /** @Field(type="int") */
        if (isset($data['count']) || (! empty($this->class->fieldMappings['count']['nullable']) && array_key_exists('count', $data))) {
            $value = $data['count'];
            if ($value !== null) {
                $typeIdentifier = $this->class->fieldMappings['count']['type'];
                $return = (int) $value;
            } else {
                $return = null;
            }
            $this->class->reflFields['count']->setValue($document, $return);
            $hydratedData['count'] = $return;
        }

        /** @Field(type="int") */
        if (isset($data['lastRequestTimeStamp']) || (! empty($this->class->fieldMappings['lastRequestTimeStamp']['nullable']) && array_key_exists('lastRequestTimeStamp', $data))) {
            $value = $data['lastRequestTimeStamp'];
            if ($value !== null) {
                $typeIdentifier = $this->class->fieldMappings['lastRequestTimeStamp']['type'];
                $return = (int) $value;
            } else {
                $return = null;
            }
            $this->class->reflFields['lastRequestTimeStamp']->setValue($document, $return);
            $hydratedData['lastRequestTimeStamp'] = $return;
        }
        return $hydratedData;
    }
}