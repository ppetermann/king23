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
 * Class with helpers to work with MongoDB
 * @throws King23_MongoException
 */
class King23_Mongo
{
    /**
     * @static
     * @throws King23_MongoException
     * @param string $input name of the collection from which to map/reduce
     * @param string $output name of the collection to write
     * @param string $map map method
     * @param string $reduce reduce method
     * @return void
     */
    public static function mapReduce($input, $output, $map, $reduce, $query = NULL)
    {
        if(!($mongo = King23_Registry::getInstance()->mongo))
            throw new King23_MongoException('mongodb is not configured');

        // emit shipType and 1
        $map = new MongoCode($map);

        // reduce to shiptype, sum of losses
        $reduce = new MongoCode($reduce);

        $cmd = array(
            "mapreduce" => $input,
            "map" => $map,
            "reduce" => $reduce,
            "out" => $output
        );
        
        // add filter query
        if(!is_null($query))
            $cmd['query'] = $query;

        // execute the mapreduce
        $mongo['db']->command($cmd);

    }
}