<?php
/*
 MIT License
 Copyright (c) 2010 - 2018 Peter Petermann

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

use Psr\Container\ContainerInterface;

class Result implements \Iterator, \Countable
{
    /**
     * @var \MongoCursor
     */
    protected $myResultCursor = null;

    /**
     * @var string
     */
    private $collection;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var ClassMapInterface
     */
    private $classMapInterface;

    /**
     * @param ContainerInterface $container
     * @param ClassMapInterface $classMapInterface
     */
    public function __construct(
        ContainerInterface $container,
        ClassMapInterface $classMapInterface
    ) {
        $this->container = $container;
        $this->classMapInterface = $classMapInterface;
    }

    /**
     * @param \MongoCursor $cursor
     */
    public function setCursor($cursor)
    {
        $this->myResultCursor = $cursor;
    }

    /**
     * @param string $collection
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
    }

    /**
     * Iterator::rewind
     */
    public function rewind()
    {
        $this->myResultCursor->rewind();
    }

    /**
     * Iterator::valid
     *
     * @return bool
     */
    public function valid()
    {
        return $this->myResultCursor->valid();
    }

    /**
     * Iterator::key
     *
     * @return string key
     */
    public function key()
    {
        return $this->myResultCursor->key();
    }

    /**
     * Iterator::next
     */
    public function next()
    {
        $this->myResultCursor->next();
    }

    /**
     * Iterator::current
     * return current specific object
     *
     * @return MongoObject
     * @throws Exception
     * @throws \MongoException
     */
    public function current()
    {
        $documentData = $this->myResultCursor->current();

        /** @var MongoObject $document */
        $document = $this->container->get(
            $this->classMapInterface->getClassForResult($this->collection, $documentData)
        );

        $document->setCollection($this->collection);
        $document->loadFromArray($documentData);

        return $document;
    }

    /**
     * count method on the cursor, allows to get result count
     *
     * @param bool $foundOnly
     * @return int
     */
    public function count($foundOnly = false)
    {
        return $this->myResultCursor->count($foundOnly);
    }

    /**
     * @return Result a sort on the cursor
     * @param array $sortoptions
     * @returns Result
     * @throws \MongoCursorException
     */
    public function sort(array $sortoptions)
    {
        $this->myResultCursor = $this->myResultCursor->sort($sortoptions);

        return $this;
    }

    /**
     * @param array $hintoptions
     * @return Result
     * @throws \MongoCursorException
     */
    public function hint(array $hintoptions)
    {
        $this->myResultCursor = $this->myResultCursor->hint($hintoptions);

        return $this;
    }

    /**
     * @param  $amount
     * @return Result
     *
     * @throws \MongoCursorException
     */
    public function limit($amount)
    {
        $this->myResultCursor = $this->myResultCursor->limit($amount);

        return $this;
    }

    /**
     * Skip the first $num results
     *
     * @param integer $num
     * @return Result
     * @throws \MongoCursorException
     */
    public function skip($num)
    {
        $this->myResultCursor->skip($num);

        return $this;
    }
}
