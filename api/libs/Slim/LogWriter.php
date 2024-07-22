<?php

namespace Slim;


class LogWriter
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * Constructor
     * @param  resource                  $resource
     * @throws \InvalidArgumentException If invalid resource
     */
    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw new \InvalidArgumentException('Cannot create LogWriter. Invalid resource handle.');
        }
        $this->resource = $resource;
    }

    public function write($message, $level = null)
    {
        return fwrite($this->resource, (string) $message . PHP_EOL);
    }
}
