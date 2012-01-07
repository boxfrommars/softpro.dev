softpro.dev
========

download
--------

http://github.com/boxfrommars/softpro.dev


install
-------

$ git clone git://github.com/boxfrommars/softpro.dev.git
$ cd deploy
$ ./deploy.sh

# <создать db>

$ cd db
mysql -u youruser yourdb -p 

mysql> \. deploy.sql
mysql> \q

cd ../..
$ vim application/app.php # изменить параметры подключения на свои
$ vim tests/phpunit.xml.dist # изменить параметры подключения на свои


тестирование
------------

$ cd tests
$ phpunit


author
------

SoftPro http://sftpr.ru