<?php

namespace BokuNo\T3EZLogger\Domain\Model;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

class EZLogger
{
  private $fileWriter;
  private $varPath;
  private $timestamp;
  private $extensionConfiguration;

  function __construct(string $filename = "ez-logging.log", bool $prependDateTime = false)
  {
    //Get config from ext_conf_template.txt
    $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get("t3ezlogger");
    //Create File
    $this->varPath = Environment::getVarPath() . "/log/";

    $this->openFileWriter($filename, $prependDateTime);
  }

  public function writeLog(array|string $msg, bool $newLine = TRUE)
  {
    if ($this->extensionConfiguration["activateLog"] == "1") {
      try {
        $this->timestamp = date("Y-m-d H:i:s");
        if (is_string($msg)) {
          fwrite($this->fileWriter, $this->timestamp . ": " . $msg);
          $this->addNewLine($newLine);
        } elseif (is_array($msg)) {
          fwrite($this->fileWriter, $this->timestamp . ": " . print_r($msg, true));
          $this->addNewLine($newLine);
        } else {
          throw new Exception('Invalid variable type for $msg');
        }
      } catch (\Throwable $th) {
        throw $th;
        $this->closeFileWriter();
      }
    }
  }

  private function addNewLine(bool $newLine)
  {
    if($newLine)
      fwrite($this->fileWriter, "\n");
  }

  public function openFileWriter(string $filename, bool $prependDateTime)
  {
    if ($prependDateTime) {
      $filename = date("Ymd_") . $filename;
    }
    $this->fileWriter = fopen($this->varPath . $filename, "a");
  }

  public function closeFileWriter()
  {
    fclose($this->fileWriter);
  }
}
