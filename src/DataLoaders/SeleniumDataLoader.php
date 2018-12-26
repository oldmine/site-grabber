<?php
/**
 * User: Daniil Zhelninskiy <webmailexec@gmail.com>
 * Date: 26.12.2018
 */

namespace oldmine\SiteGrabber\DataLoaders;


use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

class SeleniumDataLoader implements IDataLoader
{
    private $driver;

    public function __construct(string $seleniumHostUrl, DesiredCapabilities $desiredCapabilities)
    {
        $this->driver = RemoteWebDriver::create($seleniumHostUrl, $desiredCapabilities);
    }

    /**
     * @param string $url which need get data
     *
     * @return String
     */
    public function loadHTML(string $url): String
    {
        $this->driver->get($url);

        return $this->driver->getPageSource();
    }
}