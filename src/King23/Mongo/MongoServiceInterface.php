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
