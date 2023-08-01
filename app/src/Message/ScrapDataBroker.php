<?php
namespace App\Message;

class ScrapDataBroker
{
    /**
     * @var array
     */
    private array $content;

    /**
     * @param array $content
     */
    public function __construct(array $content)
    {
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }
}