<?php
/**
 * User: Daniil Zhelninskiy <webmailexec@gmail.com>
 * Date: 21.12.2018
 */

namespace oldmine\SiteGrabber\DataLoaders;


interface IDataLoader
{
    /**
     * @param string $url which need get data
     *
     * @return String
     */
    public function loadHTML(string $url): String;
}