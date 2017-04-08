<?php

namespace Tertere\FsClient\Utilities;

use Psr\Log\LoggerInterface;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class Logger
{
    const TYPE_INFO = "INFO";
    const TYPE_ERROR = "ERROR";
    const TYPE_DEBUG = "DEBUG";

    private $allTypes = [
        self::TYPE_INFO, self::TYPE_ERROR, self::TYPE_DEBUG
    ];

    protected $logger;
    protected $cloner;
    protected $dumper;

    public function __construct(LoggerInterface $logger, VarCloner $cloner, CliDumper $dumper)
    {
        $this->logger = $logger;
        $this->cloner = $cloner;
        $this->dumper = $dumper;
    }

    public function log($data, $type = self::TYPE_INFO)
    {
        $cliOutput = null;
        if (is_array($data)) {
            $cliOutput = $this->dumpArray($data);
        }

        if (is_a($data, "Exception")) {
            $cliOutput = $this->dumpException($data);
        }

        switch (strtoupper($type)) {
            case self::TYPE_DEBUG:
                $this->logger->debug($cliOutput);
                break;
            case self::TYPE_ERROR:
                $this->logger->error($cliOutput);
                break;
            case self::TYPE_INFO:
            default:
                $this->logger->info($cliOutput);
                break;
        }
    }

    public function dumpException($exception)
    {
        return $exception->getMessage() . " at line " . $exception->getLine() . " in file " . $exception->getFile();
    }

    public function dumpArray($data)
    {
        $output = null;
        $this->dumper->dump(
            $this->cloner->cloneVar($data),
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
        $this->log($message, self::TYPE_DEBUG);
    }

    public function info($message = null)
    {
        $this->log($message, self::TYPE_INFO);
    }

    public function error($message = null)
    {
        $this->log($message, self::TYPE_ERROR);
    }
}
