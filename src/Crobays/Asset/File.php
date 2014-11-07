<?php namespace Crobays\Asset;

class File extends Asset {

    /**
     * Get the File directory
     *
     * @return string
     */
    public function getUri()
    {
        return $this->fetchPath($this->getConfigItem('file'), $this->uri);
    }
}