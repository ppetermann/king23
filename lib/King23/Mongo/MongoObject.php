<?php

namespace King23\Mongo;

use Exception;

/**
 * Base class to handle objects stored in MongoDB
 *
 * @throws Exception
 */
abstract class MongoObject implements \IteratorAggregate, \ArrayAccess
{
    /**
     * @var \MongoCollection collection used by instance
     */
    protected $myCollection;

    /**
     * @var String
     */
    protected $myCollectionName;

    /**
     * @var array container to store the data of this object
     */
    protected $myData = null;

    /**
     * @var MongoServiceInterface
     */
    protected $myFactory;

    /**
     * constructor, meant to setup the object, should be called by derived classes
     *
     * @param MongoServiceInterface $factory
     */
    public function __construct(MongoServiceInterface $factory)
    {
        $this->myFactory = $factory;
    }

    /**
     * sets the collection name - this will call
     * __wakeup to ensure the right collection is loaded,
     * use with care.
     *
     * @param $collectioName
     */
    public function setCollection($collectioName)
    {
        $this->myCollectionName = $collectioName;
        $this->__wakeup();
    }

    /**
     * load data from array
     *
     * @param  $data
     * @return void
     */
    public function loadFromArray(array $data)
    {
        $this->myData = $data;
    }

    /**
     * remove the instance from the mongodb - this will not kill the object in local space however
     *
     * @return void
     */
    public function delete()
    {
        $this->myCollection->remove(['_id' => $this->myData['_id']]);
    }

    /**
     * save the object in the mongodb, will insert on new object, or update if _id is set
     *
     * @return void
     */
    public function save()
    {
        $this->myCollection->save($this->myData);
    }

    /**
     * refreshes object from database (all changes be lost!)
     *
     * @return void
     */
    public function refresh()
    {
        if ($data = $this->myCollection->findOne(['_id' => $this->_id])) {
            $this->myData = $data;
        }
    }
    // --------------------------- Iterator Fun
    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->myData);
    }
    // --------------------------- ARRAY ACCESS
    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->myData[$offset]);
    }

    /**
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->myData[$offset];
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->myData[$offset]);
    }

    /**
     * @param  $offset
     * @param  $value
     * @return mixed
     */
    public function offsetSet($offset, $value)
    {
        return $this->myData[$offset] = $value;
    }
    // --------------------------- OBJECT STYLE ACCESS
    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->myData[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->myData[$name])) {
            return $this->myData[$name];
        }

        return null;
    }
    // --------------------- unserialize
    /**
     * Magic wakeup method, will reconnect object on unserialze
     *
     * @throws Exception
     * @return void
     */
    public function __wakeup()
    {
        $this->myCollection = $this->myFactory->getDB()->selectCollection($this->myCollectionName);
    }
}
