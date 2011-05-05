<?php
/*
 MIT License
 Copyright (c) 2010 Peter Petermann

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
/**
 * Base class to handle objects stored in MongoDB
 * @throws King23_MongoException
 */
abstract class King23_MongoObject implements IteratorAggregate, ArrayAccess
{
    /**
     * @var MongoCollection collection used by instance
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
     * @static
     * @param  string $name should be the className of the class calling the method
     * @param  string $mongoid
     * @return King23_MongoObject
     */
    protected static function _getInstanceById($name, $mongoid)
    {
        $obj = new $name();
        if($data = $obj->_collection->findOne(array('_id' => new MongoId($mongoid))))
        {
            $obj->_data = $data;
            return $obj;
        }
        return null;
    }

    /**
     * conveniance method to retrieve object by criteria, should be used in
     * public static method by the derrived class
     * @static
     * @param  string $name should be the className of the class calling the method
     * @param  array $criteria
     * @return King23_MongoObject
     */
    protected static function _getInstanceByCriteria($name, $criteria)
    {
        $obj = new $name();
        if($data = $obj->_collection->findOne($criteria))
        {
            $obj->_data = $data;
            return $obj;
        }
        return null;
    }

    /**
     * @static
     * @param array $criteria
     * @return King23_MongoResult
     */
    protected static function _find($name, array $criteria)
    {
        $obj = new $name();
        return new King23_MongoResult($name, $obj->_collection->find($criteria));
    }

    /**
     * @static
     * @param string $name
     * @param array $criteria
     * @return array
     */
    public static function _findOne($name, array $criteria)
    {
        $obj = new $name();
        return $obj->_collection->findOne($criteria);
    }

    /**
     * constructor, meant to setup the object, should be called by derived classes
     * @throws King23_MongoException
     */
    public function __construct()
    {
        if(is_null($this->_className))
            throw new King23_MongoException('class name not configured in object');

        if(!($mongo = King23_Registry::getInstance()->mongo))
            throw new King23_MongoException('mongodb is not configured');
 
        $colname = $this->_className;
        $this->_collection = $mongo['db']->$colname; 
    }

    /**
     * load data from array
     * @param  $data
     * @return void
     */
    public function _loadFromArray(array $data)
    {
        $this->_data = $data;
    }

    /**
     * remove the instance from the mongodb - this will not kill the object in local space however
     * @return void
     */
    public function delete()
    {
        $this->_collection->remove(array('_id' => $this->_data['_id']));
    }

    /**
     * save the object in the mongodb, will insert on new object, or update if _id is set
     * @return void
     */
    public function save()
    {
        $this->_collection->save($this->_data);
    }

    // --------------------------- Iterator Fun

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_data);
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
        if(isset($this->_data[$name]))
            return $this->_data[$name];
        return NULL;
    }

    // --------------------- unserialize

    /**
     * Magic wakeup method, will reconnect object on unserialze
     * @throws King23_MongoException
     * @return void
     */
    public function __wakeup()
    {
        if(!($mongo = King23_Registry::getInstance()->mongo))
            throw new King23_MongoException('mongodb is not configured');

        $colname = $this->_className;
        $this->_collection = $mongo['db']->$colname;
    }

}

