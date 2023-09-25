# T3-EZ-Logger
Sometimes you just need a little workaround to debug your code

## Install
composer req bokuno/t3-ez-logger

## How to use this logger
```
use BokuNo\T3EZLogger\Domain\Model\EZLogger;
...
$ezlogger = new EZLogger("filename.log");
$ezlogger->write("I want to debug this");
...
```
check for log in var/log/filename.log
