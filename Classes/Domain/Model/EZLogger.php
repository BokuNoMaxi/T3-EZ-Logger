<?php

namespace BokuNo\T3EZLogger\Domain\Model;
use TYPO3\CMS\Core\Core\Environment;

class EZLogger {

    private $fileWriter;
    private $varPath;
    private $timestamp;

    function __construct(string $filename = "ez-logging.log") {
        $this->varPath = Environment::getVarPath()."/log/";
        $this->openFileWriter($this->varPath.$filename);

        $this->timestamp = date('Y-m-d H:i:s');
    }

    public function writeLog (string $msg){
        try {
            fwrite($this->fileWriter,$this->timestamp.": ". $msg);
        } catch (\Throwable $th) {
            //throw $th;
        }
        finally {
            $this->closeFileWriter();
        }
    }

    public function openFileWriter ($filename){
        $this->fileWriter = fopen($filename, "a");
    }

    public function closeFileWriter (){
        fclose($this->fileWriter);
    }
}
