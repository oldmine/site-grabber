<?php
/**
 * User: Daniil Zhelninskiy <webmailexec@gmail.com>
 * Date: 26.12.2018
 */

use Facebook\WebDriver\Remote\DesiredCapabilities;
use oldmine\SiteGrabber\DataLoaders\SeleniumDataLoader;
use oldmine\SiteGrabber\Grabber;
use oldmine\SiteGrabber\PageHandlers\SaveToDatabasePageHandler;
use oldmine\SiteGrabber\VisitStrategies\RecursivelyVisitStrategy;

require './vendor/autoload.php';


//Site Index URL when we want to parse
$indexUrl = '';
//Create simple handler that save page to database
$handler = new SaveToDatabasePageHandler();
//Get already handled page, if you want to stop parse and resume from old position
//$savedPosts = $handler->getSavedPages(parse_url($indexUrl)['host']);

//Create visit strategy and provide new Data Loader
$visitStrategy = new RecursivelyVisitStrategy($indexUrl, new SeleniumDataLoader('http://localhost:4444/wd/hub', DesiredCapabilities::chrome()));
//Create grabber which use provided visit strategy
$grabber = new Grabber($visitStrategy);
//Add page handler
$grabber->addPageHandler($handler);
//Run grabber
$grabber->start();
//$grabber->startFromOldPosition($savedPosts);