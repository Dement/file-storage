<?php

namespace Modules\ObjectBundle\Entity;

use BaseClasses\BaseModel;

/**
 * Objects
 */
class Objects extends BaseModel
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $key;

    /**
     * @var string
     */
    private $etag;

    /**
     * @var int|null
     */
    private $size;

    /**
     * @var bool
     */
    private $delete;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \Modules\BucketBundle\Entity\Bucket
     */
    private $bucket;


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
     * Set title.
     *
     * @param string|null $title
     *
     * @return Objects
     */
    public function setTitle($title = null)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set key.
     *
     * @param string|null $key
     *
     * @return Objects
     */
    public function setKey($key = null)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key.
     *
     * @return string|null
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set etag.
     *
     * @param string $etag
     *
     * @return Objects
     */
    public function setEtag($etag)
    {
        $this->etag = $etag;

        return $this;
    }

    /**
     * Get etag.
     *
     * @return string
     */
    public function getEtag()
    {
        return $this->etag;
    }

    /**
     * Set size.
     *
     * @param int|null $size
     *
     * @return Objects
     */
    public function setSize($size = null)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return int|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set delete.
     *
     * @param bool $delete
     *
     * @return Objects
     */
    public function setDelete($delete)
    {
        $this->delete = $delete;

        return $this;
    }

    /**
     * Get delete.
     *
     * @return bool
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Objects
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set bucket.
     *
     * @param \Modules\BucketBundle\Entity\Bucket|null $bucket
     *
     * @return Objects
     */
    public function setBucket(\Modules\BucketBundle\Entity\Bucket $bucket = null)
    {
        $this->bucket = $bucket;

        return $this;
    }

    /**
     * Get bucket.
     *
     * @return \Modules\BucketBundle\Entity\Bucket|null
     */
    public function getBucket()
    {
        return $this->bucket;
    }
    /**
     * @var \Modules\ObjectBundle\Entity\File
     */
    private $file;


    /**
     * Set file.
     *
     * @param \Modules\ObjectBundle\Entity\File|null $file
     *
     * @return Objects
     */
    public function setFile(\Modules\ObjectBundle\Entity\File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file.
     *
     * @return \Modules\ObjectBundle\Entity\File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return bool
     */
    public function isFile()
    {
        return (is_null($this->getFile())) ? false : true;
    }
}
