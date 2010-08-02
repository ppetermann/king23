<?php
class King23_MongoResult implements Iterator, Traversable
{

    /**
     * @var MongoCursor
     */
    protected $_cursor = null;

    /**
     * @var string
     */
    protected $_className= null;


    /**
     * @param  string $className
     * @param  MongoCursor $cursor
     * @return void
     */
    public function __construct($className, $cursor)
    {
        $this->_cursor = $cursor;
        $this->_className = $className;
    }


    /**
     * Iterator::rewind
     */
    public function rewind()
    {
        return $this->_cursor->rewind();
    }

    /**
     * Iterator::valid
     * @return bool
     */
    public function valid()
    {
        return $this->_cursor->valid();
    }

    /**
     * Iterator::key
     * @return string key
     */
    public function key()
    {
        return $this->_cursor->key();
    }

    /**
     * Iterator::next
     */
    public function next()
    {
        return $this->_cursor->next();
    }

    /**
     * Iterator::current
     * return current specific obect
     * @return King23_MongoObect
     */
    public function current()
    {
        $doc = $this->_cursor->current();
        $class = $this->_className;
        $k23doc = new $class();
        $k23doc->_loadFromArray($doc);
        return $k23doc;
    }
}
