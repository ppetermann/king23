# King23 PHP Framework
[![Latest Stable Version](https://poser.pugx.org/king23/king23/v/stable.png)](https://packagist.org/packages/king23/king23)
[![Total Downloads](https://poser.pugx.org/king23/king23/downloads.png)](https://packagist.org/packages/king23/king23)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/ppetermann/king23/badges/quality-score.png?s=46a1e1b22d075da22f7392cf39b88c89ab3e4b55)](https://scrutinizer-ci.com/g/ppetermann/king23/)

Copyright (C) 2010 - 2014 by Peter Petermann
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
- extendable command line task system
- ideas borrowed from better known mvc frameworks (not really a feature i guess ;)
- a simple mongodb layer 

## REQUIREMENTS
- PHP 5.3 (might run on earlier versions, untested)
- APACHE + mod_rewrite (might work on other servers with own rewrite rulesets, untested) (Mistral can be used instead, but thats highly experimental!)
- LINUX / MAC OS X (might run on windows, untested)

## INSTALLATION
1. `git clone git://github.com/ppetermann/king23.git`
2. add bin/king23 to your path (hint `sudo ln -s /path/to/king23/bin/king23 /bin/king23`)

## USAGE
New Style (recommended):
use composer:
1) http://getcomposer.org
2) `php composer.phar create-project king23/project_template DIRNAMEFORYOURNEWPROJECT`
3) Important: edit DIRNAMEFORYOURNEWPROJECT/composer.json, change projects package name etc.


Old style:
the king23 CLI comes with a simple way to create a project: (assuming bin/king23 is in path) and the project_template is installed.

`king23 King23:create_project myproject` 

this will create a folder "myproject" in the current path with a minimal king23 application, that application includes
a basic example on usage

## TODO
- more documentation
- code generators 
- add more cowbell

## LINKS
- [Homepage](http://king23.net)
- [Github](http://github.com/ppetermann/king23)
- [Twitter](http://twitter.com/King23Framework)
- [IRC](irc://irc.coldfront.net:6667/King23) bot will join and put in commit messages on commits there 

## CONTACT
- Peter Petermann <ppetermann80@googlemail.com> 

## ACKNOWLEDGEMENTS
- King23 is making use of several opensource components, such as: monolog, twig, boris
- King23 is losely based on ideas of Frameworks like Ruby on Rails or Django (but does not use any code of those)
- King23 is running on PHP (obviously), so some credit to the PHP Project here.
