<?php
/**
 * User: Daniil Zhelninskiy <webmailexec@gmail.com>
 * Date: 23.12.2018
 */

namespace oldmine\SiteGrabber;


class Page
{
    public $url;
    public $htmlData;

    public function __construct(string $url, string $htmlData)
    {
        $this->url = $url;
        $this->htmlData = $htmlData;
    }
}