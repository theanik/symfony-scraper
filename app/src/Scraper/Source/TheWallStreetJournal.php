<?php

namespace App\Scraper\Source;

use App\Scraper\Source\Contracts\SourceInterface;

class TheWallStreetJournal implements SourceInterface
{
    public function getUrl(int $page = 0): string
    {
        return "https://www.wsj.com/news/types/world-cup?page={$page}";
    }

    public function getName(): string
    {
        return 'The Wall Street Journal';
    }

    public function getWrapperSelector(): string
    {
        return '#latest-stories > article';
    }

    public function getTitleSelector(): string
    {
        return 'div.WSJTheme--content-float-right--1NZyrHNk > div.WSJTheme--headline--7VCzo7Ay > h2 > a > span';
    }

    public function getDescSelector(): string
    {
        return 'div.WSJTheme--content-float-right--1NZyrHNk > p > span';
    }

    public function getDateSelector(): string
    {
        return 'div.WSJTheme--content-float-right--1NZyrHNk > div.WSJTheme--combined-timestamp--8qWYFuAV > div > p';
    }

    public function getLinkSelector(): string
    {
        return 'div.text-content a:nth-child(2)';
    }

    public function getImageSelector(): string
    {
        return 'div.WSJTheme--image--1RvJrX_o.WSJTheme--media--2zsLCB98.WSJTheme--image-above--pBsXD1hr > a > img';
    }

    public function getTotalPaginatorSelector(): string
    {
        return 'div > div > div.WSJTheme--SimplePaginator__center--1zmFPX8Z > div > div > span';
    }
}