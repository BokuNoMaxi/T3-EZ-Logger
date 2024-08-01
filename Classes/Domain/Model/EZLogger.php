<?php

namespace BokuNo\T3EZLogger\Domain\Model;

use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Mail\MailMessage;


class EZLogger
{
  private $fileWriter;
  private string $varPath;
  private string $timestamp;
  private $extensionConfiguration;

  public function __construct(
    protected string $filename = "ez-logging.log",
    protected bool $prependDateTime = false,
    protected bool $attachToLogfile = false
  ) {
    //Get config from ext_conf_template.txt
    $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get("t3ezlogger");
    //Create File
    $this->varPath = Environment::getVarPath() . "/log/";

    $this->openFileWriter($filename, $prependDateTime);
  }

  public function writeLog(array|string $msg, bool $newLine = TRUE)
  {
    if ($this->extensionConfiguration["activateLog"] == "1") {
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
    }
  }

  private function addNewLine(bool $newLine)
  {
    if ($newLine) {
        fwrite($this->fileWriter, "\n");
    }
  }

  private function getLogFile()
  {
    return $this->varPath . $this->filename;
  }

  public function openFileWriter(string $filename, bool $prependDateTime)
  {
    if ($prependDateTime) {
      $filename = date("Ymd_") . $filename;
    }
    $mode = "w";
    if ($this->attachToLogfile) {
      $mode = "a";
    }
    $this->fileWriter = fopen($this->varPath . $filename, $mode);
  }

  public function closeFileWriter()
  {
    fclose($this->fileWriter);
  }

  /**
   * if a receiver email (normally developer E-Mail to watch for critical tasks) is set in the extension configuration it will send the log file
   * you can set a subject and a default sender address,
   * as fallback for the sender address the one from the extension configuration will be used and the fallback from the configuration is the typo3 default sender address
   */
  public function sendMail(string $subject = "", Address|string $to = "", Address|string $sender = ""): bool
  {
    if (
      $this->extensionConfiguration["activateLog"] == "1" &&
      ($to || $this->extensionConfiguration["mailReceiver"])
    ) {
      $to = $to ?: $this->extensionConfiguration["mailReceiver"];
      $mail = GeneralUtility::makeInstance(MailMessage::class);
      $mail
        ->setSubject($subject ?? 'Report from EZ Logger')
        ->setTo($to)
        ->text("Hello, \n\nA new logfile has been generated. You can find it attached to this mail. \n\nHave a nice day!")
        ->attachFromPath($this->getLogFile(), 'logfile.log');
      if ($sender || $this->extensionConfiguration["mailSender"]) {
        $sender = $sender ?: $this->extensionConfiguration["mailSender"];
        $mail
          ->from($sender)
          ->sender($sender);
      }

      return $mail->send();
    }
    return false;
  }
}
