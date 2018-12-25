<?php
/**
 * User: Daniil Zhelninskiy <webmailexec@gmail.com>
 * Date: 21.12.2018
 */

namespace oldmine\SiteGrabber\PageHandlers;


interface IPageHandler
{
    /**
     * @param string $url page url
     * @param string $data page html data
     *
     * @return bool Handler have processed page.
     *              True - if handler have processed page.
     *              False - if handler can't process page.
     */
    public function handle(string $url, string $data): bool;
}