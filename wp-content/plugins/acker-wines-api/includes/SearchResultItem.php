<?php
namespace AckerWines\Api;


class SearchResultItem
{
    private $id = 0;
    private $title = '';
    private $link = '';
    private $thumb = '';

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }
    /**
     * @return mixed
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    public function toArray()
    {
        return array(
            'id' => $this->$id,
            'title' => $this->$title,
            'link' => $this->$link,
            'thumb' => $this->$thumb,
        );
    }
}
