<?php
namespace King23\Mongo;

/**
 * Class CollectionNameIsClassMap
 * this allows for simple name to collection mapping - downside,
 * you should only use one namespace for your Model files this way.
 *
 * @package King23\Mongo
 */
class CollectionNameIsClassMap implements ClassMapInterface
{
    /**
     * @var string
     */
    protected $namespace = '\\';

    /**
     * set a namespace prefix that is used for the class
     * @param $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @param string
     * @param array $result
     * @return string
     */
    public function getClassForResult($collectionName, $result)
    {
        return $this->namespace . $collectionName;
    }
}
