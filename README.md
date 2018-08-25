[![License](https://poser.pugx.org/king23/king23/license.png)](https://packagist.org/packages/king23/king23)
[![Latest Stable Version](https://poser.pugx.org/king23/king23/v/stable.png)](https://packagist.org/packages/king23/king23)
[![Total Downloads](https://poser.pugx.org/king23/king23/downloads.png)](https://packagist.org/packages/king23/king23)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/ppetermann/king23/badges/quality-score.png?s=46a1e1b22d075da22f7392cf39b88c89ab3e4b55)](https://scrutinizer-ci.com/g/ppetermann/king23/)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1ecb1847-a15f-4c00-9e80-374a701bc000/mini.png)](https://insight.sensiolabs.com/projects/1ecb1847-a15f-4c00-9e80-374a701bc000)
# King23 PHP Framework

Copyright (C) 2010 - 2018 by Peter Petermann
All rights reserved.

King23 is a small and simple MVC Framework for PHP

## WARNING
King23 is not having a stable release yet, usage on your own risk,
compatibility might break!

## LICENSE
King23 is licensed under a MIT style license, see LICENSE.txt 
for further information

## FEATURES
- automated class loading
- url router
- ideas borrowed from better known mvc frameworks (not really a feature i guess ;)
- a simple mongodb layer 

## REQUIREMENTS
- PHP 7.2 (might run on earlier versions, untested)
- a Webserver (nginx recommended, apache possible, mistral / reactphp experimental)
- LINUX / MAC OS X (might run on windows, untested)

## USAGE
New Style (recommended):
use composer:
1) http://getcomposer.org
2) `php composer.phar create-project king23/skeleton DIRNAMEFORYOURNEWPROJECT`
3) Important: edit DIRNAMEFORYOURNEWPROJECT/composer.json, change projects package name etc.

## TODO
- more documentation
- code generators 
- add more cowbell

## LINKS
- [Homepage](http://king23.net)
- [Github](http://github.com/ppetermann/king23)
- [Twitter](http://twitter.com/ppetermann)
- [IRC](irc://irc.coldfront.net:6667/King23) bot will join and put in commit messages on commits there 

## CONTACT
- Peter Petermann <ppetermann80@googlemail.com> 

## ACKNOWLEDGEMENTS
- King23 is making use of several opensource components, such as: monolog, twig, boris
- King23 is losely based on ideas of Frameworks like Ruby on Rails or Django (but does not use any code of those)
- King23 is running on PHP (obviously), so some credit to the PHP Project here.
