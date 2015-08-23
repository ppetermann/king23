<?php

namespace King23\Mongo;

use King23\DI\ContainerInterface;

class Result implements \Iterator, \Countable
{
    /**
     * @var \MongoCursor
     */
    protected $myResultCursor = null;

    /**
     * @var MongoServiceInterface
     */
    private $factory;

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
     * @param MongoServiceInterface $factory
     * @param ContainerInterface $container
     * @param ClassMapInterface $classMapInterface
     */
    public function __construct(
        MongoServiceInterface $factory,
        ContainerInterface $container,
        ClassMapInterface $classMapInterface
    ) {
        $this->factory = $factory;
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
     */
    public function current()
    {
        $documentData = $this->myResultCursor->current();

        /** @var MongoObject $document */
        $document = $this->container->getInstanceOf(
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
     */
    public function sort(array $sortoptions)
    {
        $this->myResultCursor = $this->myResultCursor->sort($sortoptions);

        return $this;
    }

    /**
     * @param array $hintoptions
     * @returns Result
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
     */
    public function skip($num)
    {
        $this->myResultCursor->skip($num);

        return $this;
    }
}
