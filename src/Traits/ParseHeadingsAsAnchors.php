<?php

namespace Vulcan\UserDocs\Traits;

use SilverStripe\Control\Controller;
use SilverStripe\View\Parsers\ShortcodeParser;
use SilverStripe\View\Parsers\URLSegmentFilter;
use Sunra\PhpSimple\HtmlDomParser;

/**
 * Trait ParseHeadingsAsAnchors
 * @package Vulcan\UserDocs\Traits
 */
trait ParseHeadingsAsAnchors
{
    /** @var array Unique slug storage to ensure there are no duplicates */
    private $anchors = [];

    /**
     * @param null|string $content Optionally force other content into this method to be parsed
     *
     * @param null|string $link An optional base url for the anchor,  by default it will attempt $this->Link()
     *
     * @return string
     */
    public function getAnchoredContent($content = null, $link = null)
    {
        $content = ($content) ?: $this->Content;
        $link = $link ?: $this->Link();

        $parser = HtmlDomParser::str_get_html((string)$content);
        if (!$parser) {
            return $content;
        }
        $headings = $parser->find('h1,h2,h3,h4,h5');
        if ($headings) {
            foreach ($headings as $heading) {
                $text = $heading->innertext();
                $slug = $this->getSlugFromHeading($text);
                $heading->setAttribute('id', $slug);
                $heading->innertext = sprintf("<a href='%s#%s'>%s</a>", $link, $slug, $text);
            }
        }

        return ShortcodeParser::get_active()->parse((string)$parser);
    }

    /**
     * @param $heading
     *
     * @return string
     * @throws \Exception
     */
    public function getSlugFromHeading($heading)
    {
        $filter = URLSegmentFilter::create();

        for ($i = 1; $i < 1000; $i++) {
            $slug = $filter->filter($heading) . (($i > 1) ? "-$i" : '');
            if (!in_array($slug, $this->anchors)) {
                $this->anchors[] = $slug;
                return $slug;
            }
        }

        throw new \Exception('Unable to generate a slug after 1000 attempts');
    }
}
