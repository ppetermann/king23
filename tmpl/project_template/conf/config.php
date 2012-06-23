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

if(!defined("APP_PATH"))
    define("APP_PATH", realpath(dirname(__FILE__) . "/.."));

require_once(APP_PATH . "/vendor/king23/king23/lib/core/King23_Classloader.php");
King23_Classloader::register();

King23_Classloader::init(APP_PATH . "/vendor/king23/king23/lib");
King23_Classloader::init(APP_PATH . "/views");

$reg = King23_Registry::getInstance();

// composer autoload
require_once APP_PATH . "/vendor/autoload.php";

// Twig Template configuration
Twig_Autoloader::register();
$reg->twig = new Twig_Environment(new Twig_Loader_Filesystem(APP_PATH ."/templates"), array(
    "cache" => APP_PATH . "/templates_c",
    "auto_reload" => true // remove to disabled recompiling
));


require_once("routes.php");

// uncomment the next line if you have Doctrine installed, and configured in the doctrine.php
//require_once("doctrine.php");
