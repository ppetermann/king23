<?php
namespace King23\Mongo;

use King23\DI\ContainerInterface;

class MongoService implements MongoServiceInterface
{
    /**
     * @var \MongoDB
     */
    protected $dbConnection;

    /**
     * @var ClassMapInterface
     */
    protected $classMap;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param \MongoDB $dbConnection
     * @param ClassMapInterface $classMap
     * @param ContainerInterface $container
     */
    public function __construct(\MongoDB $dbConnection, ClassMapInterface $classMap, ContainerInterface $container)
    {
        $this->dbConnection = $dbConnection;
        $this->classMap = $classMap;
        $this->container = $container;
    }

    /**
     * @return \MongoDB
     */
    public function getDB()
    {
        return $this->dbConnection;
    }

    /**
     * convenience method to retrieve object by id, should be used in
     * public static method by the derived class
     *
     * @param string $collection
     * @param string $mongoId
     * @return MongoObject
     */
    public function getById($collection, $mongoId)
    {
        return $this->getByCriteria($collection, ['_id' => new \MongoId($mongoId)]);
    }

    /**
     * convenience method to retrieve object by criteria, should be used in
     * public static method by the derived class
     *
     * @param string $collection
     * @param  array $criteria
     * @return MongoObject
     * @throws Exception
     */
    public function getByCriteria($collection, $criteria)
    {
        if ($data = $this->findOne($collection, $criteria)) {

            /** @var MongoObject $obj */
            $obj = $this->container->getInstanceOf(
                $this->classMap->getClassForResult($collection, $data)
            );
            $obj->setCollection($collection);
            $obj->loadFromArray($data);

            return $obj;
        }

        return null;
    }

    /**
     * @param string $collection
     * @param array $criteria
     * @param array $fields
     * @return Result
     * @throws Exception
     */
    public function find($collection, array $criteria, array $fields = [])
    {
        $data = $this->dbConnection->selectCollection($collection)->find($criteria, $fields);

        /** @var Result $result */
        $result = $this->container->getInstanceOf(Result::class);
        $result->setCollection($collection);
        $result->setCursor($data);

        return $result;
    }

    /**
     * Run Aggregation through the Aggregation Pipeline
     *
     * @param string $collection
     * @param array $pipeline
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    public function aggregate($collection, array $pipeline, $options = [])
    {
        $data = $this->dbConnection->selectCollection($collection)->aggregate($pipeline);
        if ($data['ok'] != 1) {
            throw new Exception("Tool Aggregation Error: ".$data['errmsg'], $data['code']);
        }

        return $data['result'];
    }

    /**
     * @param string $collection
     * @param string $fieldname
     * @param array $criteria
     * @return array
     */
    public function distinct($collection, $fieldname, array $criteria = [])
    {
        return $this->dbConnection->selectCollection($collection)->distinct($fieldname, $criteria);
    }

    /**
     * returns the first found matching document
     *
     * @param string $collection
     * @param array $criteria
     * @param array $fields
     * @return array
     */
    public function findOne($collection, array $criteria, array $fields = [])
    {
        return $this->dbConnection->selectCollection($collection)->findOne($criteria, $fields);
    }

    /**
     * conveniant method to create new instances
     * @param string $collection
     * @return MongoObject
     */
    public function newObject($collection)
    {
        /** @var MongoObject $obj */
        $obj =$this->container->getInstanceOf($this->classMap->getClassForResult($collection, []));
        $obj->setCollection($collection);

        return $obj;
    }

    /**
     * @param string $input name of the collection from which to map/reduce
     * @param string $output name of the collection to write
     * @param string $map map method
     * @param string $reduce reduce method
     * @param array $query criteria to apply
     * @param array $additional
     * @return mixed
     */
    public function mapReduce($input, $output, $map, $reduce, $query = null, $additional = array())
    {
        $mongo = $this->getDB();
        $map = new \MongoCode($map);
        $reduce = new \MongoCode($reduce);
        $cmd = array(
            "mapreduce" => $input,
            "map" => $map,
            "reduce" => $reduce,
            "out" => $output
        );
        // add filter query
        if (!is_null($query)) {
            $cmd['query'] = $query;
        }
        $cmd = array_merge($cmd, $additional);

        // execute the mapreduce
        return $mongo->command($cmd);
    }
}
