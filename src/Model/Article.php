<?php
namespace Scraper\Model;

class Article
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $link;

    /**
     * @var string
     */
    private $metaDescription;

    /**
     * @var string
     */
    private $metaKeywords;

    /**
     * @var float
     */
    private $fileSize;

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLink() : string
    {
        return $this->link;
    }

    /**
     * @param float $fileSize
     */
    public function setFileSize(float $fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return float
     */
    public function getFileSize() : float
    {
        return $this->fileSize;
    }

    /**
     * @return string
     */
    public function getFileSizeText() : string
    {
        return $this->fileSize . 'kb';
    }

    /**
     * @param string $description
     */
    public function setMetaDescription(string $description)
    {
        $this->metaDescription = $description;
    }

    /**
     * @return string
     */
    public function getMetaDescription() : ?string
    {
        return $this->metaDescription;
    }

    /**
     * @param string $keywords
     */
    public function setMetaKeywords(string $keywords)
    {
        $this->metaKeywords = $keywords;
    }

    /**
     * @return string
     */
    public function getMetaKeywords() : ?string
    {
        return $this->metaKeywords;
    }
}