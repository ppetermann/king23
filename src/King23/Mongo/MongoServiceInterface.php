<?php
namespace King23\Mongo;

interface MongoServiceInterface
{
    /**
     * @return \MongoDB
     */
    public function getDB();

    /**
     * convenience method to retrieve object by id, should be used in
     * public static method by the derived class
     *
     * @param string $collection
     * @param string $mongoId
     * @return MongoObject
     */
    public function getById($collection, $mongoId);

    /**
     * convenience method to retrieve object by criteria, should be used in
     * public static method by the derived class
     *
     * @param string $collection
     * @param  array $criteria
     * @return MongoObject
     * @throws Exception
     */
    public function getByCriteria($collection, $criteria);

    /**
     * @param string $collection
     * @param array $criteria
     * @param array $fields
     * @return Result
     * @throws Exception
     */
    public function find($collection, array $criteria, array $fields = []);

    /**
     * Run Aggregation through the Aggregation Pipeline
     *
     * @param string $collection
     * @param array $pipeline
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    public function aggregate($collection,array $pipeline, $options = []);

    /**
     * @param string $collection
     * @param string $fieldname
     * @param array $criteria
     * @return array
     */
    public function distinct($collection, $fieldname, array $criteria = []);

    /**
     * returns the first found matching document
     *
     * @param string $collection
     * @param array $criteria
     * @param array $fields
     * @return array
     */
    public function findOne($collection, array $criteria, array $fields = []);


    /**
     * create a new instance of an mongo object
     *
     * @param string $collection
     * @return MongoObject
     */
    public function newObject($collection);

    /**
     * @param string $input name of the collection from which to map/reduce
     * @param string $output name of the collection to write
     * @param string $map map method
     * @param string $reduce reduce method
     * @param array $query criteria to apply
     * @param array $additional
     * @return mixed
     */
    public function mapReduce($input, $output, $map, $reduce, $query = null, $additional = array());
}
