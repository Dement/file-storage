<?php

namespace Modules\ObjectBundle\Entity;
use BaseClasses\BaseModel;

/**
 * File
 */
class File extends BaseModel
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $ext;

    /**
     * @var int
     */
    private $size;

    /**
     * @var \Modules\ObjectBundle\Entity\Objects
     */
    private $Objects;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set fileName.
     *
     * @param string $fileName
     *
     * @return File
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set ext.
     *
     * @param string $ext
     *
     * @return File
     */
    public function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Get ext.
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * Set size.
     *
     * @param int $size
     *
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set objects.
     *
     * @param \Modules\ObjectBundle\Entity\Objects|null $objects
     *
     * @return File
     */
    public function setObjects(\Modules\ObjectBundle\Entity\Objects $objects = null)
    {
        $this->Objects = $objects;

        return $this;
    }

    /**
     * Get objects.
     *
     * @return \Modules\ObjectBundle\Entity\Objects|null
     */
    public function getObjects()
    {
        return $this->Objects;
    }
}
