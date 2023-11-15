# T3-EZ-Logger
Sometimes you just need a little workaround to debug your code

## Install
composer req bokuno/t3-ez-logger

## How to use this logger
The logger needs to be activated to write into the log file. So you can easily de-/activate this feature without installing and removing the extension. 

Then you can use this code to log: 
```
use BokuNo\T3EZLogger\Domain\Model\EZLogger;
...
$ezlogger = new EZLogger("filename.log");
$ezlogger->write("I want to debug this");
...
```
check for log in var/log/filename.log

## But why ? I can log directly via \TYPO3\CMS\Core\Log\LogManager

Because when I need to debug strange things in production, I don't want to flood the server with unnecessery logs from extensions I cannot fix for reasons. 

##TODO:
- Scheduler to remove log after n days
- Send Mail Function to directly send your log via mail to predefined mail adress
