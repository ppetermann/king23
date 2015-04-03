<?php
/*
 MIT License
 Copyright (c) 2010 - 2015 Peter Petermann

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
use King23\Core\Registry;
use King23\Mongo\Exceptions\MongoException;

/**
 * Class with helpers to work with MongoDB
 *
 * @throws MongoException
 */
class Mongo
{
    /**
     * Method that caches inline map/reduces to allow for transparent cache
     * all restrictions applying to inline map/reduce apply to this!
     *
     * @static
     * @param string $class name of the map/reduce class (hint: __class__)
     * @param integer $cachetime seconds to cache
     * @param string $collection name of the collection from which to map/reduce
     * @param string $map map method
     * @param string $reduce reduce method
     * @param array $query criteria to apply
     * @internal param string $output name of the collection to write
     * @return mixed
     */
    public static function cachedMapReduce($class, $cachetime, $collection, $map, $reduce, $query)
    {
        $hash = md5($collection.$map.$reduce.join(':', $query).join(':', array_keys($query)));
        $obj = MongoObject::doGetInstanceByCriteria($class, array('hash' => $hash));

        if (is_null($obj) || time() > ($obj->updated->sec + $cachetime)) {
            if (is_null($obj)) {
                $obj = new $class();
                $obj->hash = $hash;
            }
            $result = self::mapReduce($collection, array('inline' => 1), $map, $reduce, $query);
            $obj->result = $result;
            $obj->updated = new \MongoDate(time());
            $obj->save();
            return $result;
        }

        return $obj->result;
    }

    /**
     * @return mixed
     * @throws Exceptions\MongoException
     */
    public static function getMongoConfig()
    {
        if (!($mongo = Registry ::getInstance()->mongo)) {
            throw new MongoException('mongodb is not configured');
        }
        return $mongo;
    }

    /**
     * @static
     *
     * @param string $input name of the collection from which to map/reduce
     * @param string $output name of the collection to write
     * @param string $map map method
     * @param string $reduce reduce method
     * @param array $query criteria to apply
     * @param array $additional
     * @return mixed
     */
    public static function mapReduce($input, $output, $map, $reduce, $query = null, $additional = array())
    {
        $mongo = Mongo::getMongoConfig();

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
        return $mongo['db']->command($cmd);

    }
}
