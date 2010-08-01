<?php
/**
 * Base class to handle objects stored in MongoDB
 * @throws King23_MongoException
 */
abstract class King23_MongoObject implements IteratorAggregate
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
        $obj->_data = $obj->_collection->findOne(array('_id' => new MongoId($mongoid)));
        return $obj;
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
        return $this->data[$name];
    }
}

