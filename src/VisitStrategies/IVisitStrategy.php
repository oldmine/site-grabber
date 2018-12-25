<?php
/**
 * User: Daniil Zhelninskiy <webmailexec@gmail.com>
 * Date: 22.12.2018
 */

namespace oldmine\SiteGrabber\VisitStrategies;


interface IVisitStrategy
{
    /**
     * Returns a generator that provides Pages objects
     *
     * @return \Generator
     * @yield Page
     */
    public function getGenerator(): \Generator;
}