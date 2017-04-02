<?php

namespace Tertere\Utilities;

use Psr\Log\LoggerInterface;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class Logger
{
    const TYPE_INFO = "INFO";
    const TYPE_ERROR = "ERROR";
    const TYPE_DEBUG = "DEBUG";

    protected $logger;
    protected $cloner;
    protected $dumper;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->cloner = new VarCloner();
        $this->dumper = new CliDumper();
    }

    public function log($data, $type = self::TYPE_INFO) {
        $cliOutput = null;
        if (is_array($data)) {
            $cliOutput = $this->dumpArray($data);
        }

        if (is_a($data, "Exception")) {
            $cliOutput = $this->dumpException($data);
        }

        switch (strtoupper($type)) {
            case self::TYPE_DEBUG:
                $this->debug($cliOutput);
                break;
            case self::TYPE_ERROR:
                $this->error($cliOutput);
                break;
            case self::TYPE_INFO:
                $this->info($cliOutput);
                break;
        }
    }

    public function dumpException($exception) {
        return $exception->getMessage() . " at line " . $exception->getLine() . " in file " . $exception->getFile();
    }

    public function dumpArray($data) {
        $output = null;
        $this->dumper->dump(
            $this->cloner->cloneVar($variable),
            function ($line, $depth) use (&$output) {
                // A negative depth means "end of dump"
                if ($depth >= 0) {
                    // Adds a two spaces indentation to the line
                    $output .= str_repeat('  ', $depth).$line."\n";
                }
            }
        );
        return $output;
    }

    public function debug($message = null)
    {
        $this->logger->debug($message);
    }

    public function info($message = null)
    {
        $this->logger->log($message);
    }

    public function error($message = null)
    {
        $this->logger->error($message);
    }
}