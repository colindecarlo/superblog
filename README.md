superblog
=========

A demo blog application built with Silex for the Guelph Web Maker Meetup

requirements
============

* PHP 5.4 (but only because of the shortened array syntax so if you want replace all the '[' / ']' with 'array(' / ')' it should work)
* mysql
* [composer](http://getcomposer.org)
* [liquibase](http://www.liquibase.org)

installation
============

* clone the repo
* install the applications dependencies with composer
* create the superblog database
* create a `liquibase.properties` file in the `migrations` directory
** there is an example in the `migrations` directory called `liquibase.properties.example` that should be useful
* run liquibase update to run the migrations in the `migrations` directory
