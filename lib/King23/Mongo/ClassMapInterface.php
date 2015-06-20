<?php
namespace King23\Mongo;

interface ClassMapInterface
{
    /**
     * @param string
     * @param array $result
     * @return string
     */
    public function getClassForResult($collectionName, $result);
}
