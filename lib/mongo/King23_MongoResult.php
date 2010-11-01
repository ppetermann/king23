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
class King23_MongoResult implements Iterator, Countable
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
     * @return King23_MongoObject
     */
    public function current()
    {
        $doc = $this->_cursor->current();
        $class = $this->_className;
        $k23doc = new $class();
        $k23doc->_loadFromArray($doc);
        return $k23doc;
    }
    
    /**
    * count method on the cursor, allows to get result count
    */ 
    public function count()
    {
        return $this->_cursor->count();
    }

    /**
     * @return run a sort on the cursor
     * @param array sort options
     * @returns King23_MongoResult
     */
    public function sort(array $sortoptions)
    {
        $this->_cursor = $this->_cursor->sort($sortoptions);
        return $this;
    }

    /**
     * @param  $amount
     * @return King23_MongoResult
     *
     */
    public function limit($amount)
    {
        $this->_cursor = $this->_cursor->limit($amount);
        return $this;
    }
}
