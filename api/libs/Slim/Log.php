<?php

namespace Slim;


class Log
{
    const EMERGENCY = 1;
    const ALERT     = 2;
    const CRITICAL  = 3;
    const FATAL     = 3; //DEPRECATED replace with CRITICAL
    const ERROR     = 4;
    const WARN      = 5;
    const NOTICE    = 6;
    const INFO      = 7;
    const DEBUG     = 8;

    /**
     * @var array
     */
    protected static $levels = array(
        self::EMERGENCY => 'EMERGENCY',
        self::ALERT     => 'ALERT',
        self::CRITICAL  => 'CRITICAL',
        self::ERROR     => 'ERROR',
        self::WARN      => 'WARNING',
        self::NOTICE    => 'NOTICE',
        self::INFO      => 'INFO',
        self::DEBUG     => 'DEBUG'
    );

    /**
     * @var mixed
     */
    protected $writer;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var int
     */
    protected $level;

    /**
     * Constructor
     * @param  mixed $writer
     */
    public function __construct($writer)
    {
        $this->writer = $writer;
        $this->enabled = true;
        $this->level = self::DEBUG;
    }

    /**
     * Is logging enabled?
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Enable or disable logging
     * @param  bool $enabled
     */
    public function setEnabled($enabled)
    {
        if ($enabled) {
            $this->enabled = true;
        } else {
            $this->enabled = false;
        }
    }

    /**
     * Set level
     * @param  int                          $level
     * @throws \InvalidArgumentException    If invalid log level specified
     */
    public function setLevel($level)
    {
        if (!isset(self::$levels[$level])) {
            throw new \InvalidArgumentException('Invalid log level');
        }
        $this->level = $level;
    }

    /**
     * Get level
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set writer
     * @param  mixed $writer
     */
    public function setWriter($writer)
    {
        $this->writer = $writer;
    }

    /**
     * Get writer
     * @return mixed
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * Is logging enabled?
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }


    public function debug($object, $context = array())
    {
        return $this->log(self::DEBUG, $object, $context);
    }

 
    public function info($object, $context = array())
    {
        return $this->log(self::INFO, $object, $context);
    }

  
    public function notice($object, $context = array())
    {
        return $this->log(self::NOTICE, $object, $context);
    }

 
    public function warning($object, $context = array())
    {
        return $this->log(self::WARN, $object, $context);
    }

    /**
     * DEPRECATED for function warning
     * Log warning message
     * @param  mixed       $object
     * @param  array       $context
     * @return mixed|bool What the Logger returns, or false if Logger not set or not enabled
     */
    public function warn($object, $context = array())
    {
        return $this->log(self::WARN, $object, $context);
    }

    /**
     * Log error message
     * @param  mixed       $object
     * @param  array       $context
     * @return mixed|bool What the Logger returns, or false if Logger not set or not enabled
     */
    public function error($object, $context = array())
    {
        return $this->log(self::ERROR, $object, $context);
    }

    /**
     * Log critical message
     * @param  mixed       $object
     * @param  array       $context
     * @return mixed|bool What the Logger returns, or false if Logger not set or not enabled
     */
    public function critical($object, $context = array())
    {
        return $this->log(self::CRITICAL, $object, $context);
    }

    /**
     * DEPRECATED for function critical
     * Log fatal message
     * @param  mixed       $object
     * @param  array       $context
     * @return mixed|bool What the Logger returns, or false if Logger not set or not enabled
     */
    public function fatal($object, $context = array())
    {
        return $this->log(self::CRITICAL, $object, $context);
    }

    /**
     * Log alert message
     * @param  mixed       $object
     * @param  array       $context
     * @return mixed|bool What the Logger returns, or false if Logger not set or not enabled
     */
    public function alert($object, $context = array())
    {
        return $this->log(self::ALERT, $object, $context);
    }

    /**
     * Log emergency message
     * @param  mixed       $object
     * @param  array       $context
     * @return mixed|bool What the Logger returns, or false if Logger not set or not enabled
     */
    public function emergency($object, $context = array())
    {
        return $this->log(self::EMERGENCY, $object, $context);
    }


    public function log($level, $object, $context = array())
    {
        if (!isset(self::$levels[$level])) {
            throw new \InvalidArgumentException('Invalid log level supplied to function');
        } else if ($this->enabled && $this->writer && $level <= $this->level) {
            $message = (string)$object;
            if (count($context) > 0) {
                if (isset($context['exception']) && $context['exception'] instanceof \Exception) {
                    $message .= ' - ' . $context['exception'];
                    unset($context['exception']);
                }
                $message = $this->interpolate($message, $context);
            }
            return $this->writer->write($message, $level);
        } else {
            return false;
        }
    }

    /**
     * DEPRECATED for function log
     * Log message
     * @param   mixed    $object The object to log
     * @param   int      $level  The message level
     * @return  int|bool
     */
    protected function write($object, $level)
    {
        return $this->log($level, $object);
    }

    /**
     * Interpolate log message
     * @param  mixed     $message               The log message
     * @param  array     $context               An array of placeholder values
     * @return string    The processed string
     */
    protected function interpolate($message, $context = array())
    {
        $replace = array();
        foreach ($context as $key => $value) {
            $replace['{' . $key . '}'] = $value;
        }
        return strtr($message, $replace);
    }
}
