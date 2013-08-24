<?php
/*
 MIT License
 Copyright (c) 2010 - 2013 Peter Petermann

 Permission is hereby granted, free of charge, to any person
 obtaining a copy of this software and associated documentation
 files (the "Software"), to deal in the Software without
 restriction, including without limitation the rights to use,
 copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the
 Software is furnished to do so, subject to the following
 conditions:

 The above copyright notice and this permission notice shall be
 included in all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 OTHER DEALINGS IN THE SOFTWARE.

*/
namespace King23\Mongo;

/**
 * Base class to handle objects stored in MongoDB
 *
 * @throws \King23\Mongo\Exceptions\MongoException
 */
abstract class MongoObject implements \IteratorAggregate, \ArrayAccess
{
    /**
     * @var \MongoCollection collection used by instance
     */
    protected $_collection;

    /**
     * @var string ensure this is overwritten by the derived classes!
     */
    protected $_className = null;

    /**
     * @var array container to store the data of this obect
     */
    protected $_data = null;

    /**
     * conveniance method to retrieve object by id, should be used in
     * public static method by the derrived class
     *
     * @static
     * @param  string $name should be the className of the class calling the method
     * @param  string $mongoid
     * @return MongoObject
     */
    protected static function getInstanceById($name, $mongoid)
    {
        $obj = new $name();
        if ($data = $obj->_collection->findOne(array('_id' => new \MongoId($mongoid)))) {
            $obj->_data = $data;
            return $obj;
        }
        return null;
    }

    /**
     * conveniance method to retrieve object by criteria, should be used in
     * public static method by the derrived class
     *
     * @static
     * @param  string $name should be the className of the class calling the method
     * @param  array $criteria
     * @return MongoObject
     */
    public static function getInstanceByCriteria($name, $criteria)
    {
        $obj = new $name();
        if ($data = $obj->_collection->findOne($criteria)) {
            $obj->_data = $data;
            return $obj;
        }
        return null;
    }

    /**
     * @static
     * @param $name
     * @param array $criteria
     * @param array $fields
     * @return MongoResult
     */
    protected static function find($name, array $criteria, array $fields = array())
    {
        $obj = new $name();
        return new MongoResult($name, $obj->_collection->find($criteria, $fields));
    }

    /**
     * @static
     * @param string $name
     * @param string $fieldname
     * @param array $criteria
     * @return array
     */
    protected static function distinct($name, $fieldname, array $criteria = array())
    {
        $obj = new $name();
        return $obj->_collection->distinct($fieldname, $criteria);
    }


    /**
     * @static
     * @param string $name
     * @param array $criteria
     * @param array $fields
     * @return array
     */
    public static function findOne($name, array $criteria, array $fields = array())
    {
        $obj = new $name();
        return $obj->_collection->findOne($criteria, $fields);
    }

    /**
     * constructor, meant to setup the object, should be called by derived classes
     *
     * @throws \King23\Mongo\Exceptions\MongoException
     */
    public function __construct()
    {
        if (is_null($this->_className)) {
            throw new \King23\Mongo\Exceptions\MongoException('class name not configured in object');
        }

        $this->__initialize();
    }

    /**
     * load data from array
     *
     * @param  $data
     * @return void
     */
    public function loadFromArray(array $data)
    {
        $this->_data = $data;
    }

    /**
     * remove the instance from the mongodb - this will not kill the object in local space however
     *
     * @return void
     */
    public function delete()
    {
        $this->_collection->remove(array('_id' => $this->_data['_id']));
    }

    /**
     * save the object in the mongodb, will insert on new object, or update if _id is set
     *
     * @return void
     */
    public function save()
    {
        $this->_collection->save($this->_data);
    }

    /**
     * refreshes object from database (all changes be lost!)
     *
     * @return void
     */
    public function refresh()
    {
        if ($data = $this->_collection->findOne(array('_id' => $this->_id))) {
            $this->_data = $data;
        }
    }

    // --------------------------- Iterator Fun

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_data);
    }


    // --------------------------- ARRAY ACCESS
    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    /**
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->_data[$offset];
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }

    /**
     * @param  $offset
     * @param  $value
     * @return mixed
     */
    public function offsetSet($offset, $value)
    {
        return $this->_data[$offset] = $value;
    }

    // --------------------------- OBJECT STYLE ACCESS

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->_data[$name])) {
            return $this->_data[$name];
        }
        return null;
    }

    // --------------------- unserialize

    /**
     * Magic wakeup method, will reconnect object on unserialze
     *
     * @throws \King23\Mongo\Exceptions\MongoException
     * @return void
     */
    public function __wakeup()
    {
        $this->__initialize();
    }

    /**
     * initialize mongodb connections
     *
     * @throws Exceptions\MongoException
     */
    protected function __initialize()
    {
        $mongo = Mongo::getMongoConfig();
        $colname = $this->_className;
        $this->_collection = $mongo['db']->$colname;
    }
}
