<?php

namespace BokuNo\T3EZLogger\Domain\Model;
use TYPO3\CMS\Core\Core\Environment;

class EZLogger {

    private $fileWriter;
    private $varPath;

    function __construct(string $filename = "ez-logging.log") {
        $this->varPath = Environment::getVarPath()."/log/";
        $this->openFileWriter($this->varPath.$filename);
    }

    public function writeLog (string $msg){
        try {
            fwrite($this->fileWriter,$msg);
        } catch (\Throwable $th) {
            //throw $th;
        }
        finally {
            $this->closeFileWriter();
        }
    }

    public function openFileWriter ($filename){
        $this->fileWriter = fopen($filename,"w");
    }

    public function closeFileWriter (){
        fclose($this->fileWriter);
    }
}
