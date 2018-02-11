<?php

namespace Vulcan\UserDocs\Traits;

use SilverStripe\Control\Controller;
use SilverStripe\View\Parsers\ShortcodeParser;
use SilverStripe\View\Parsers\URLSegmentFilter;
use Sunra\PhpSimple\HtmlDomParser;

trait ParseHeadingsAsAnchors
{
    private $anchors = [];

    /**
     * @param null $content
     *
     * @return string
     */
    public function getAnchoredContent($content = null)
    {
        $content = ($content) ? $content : $this->Content;

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
                $heading->innertext = sprintf("<a href='%s#%s'>%s</a>", Controller::curr()->Link(), $slug, $text);
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
    private function getSlugFromHeading($heading)
    {
        $filter = URLSegmentFilter::create();

        for ($i = 0; $i < 1000; $i++) {
            $slug = $filter->filter($heading) . (($i) ? "-$i" : '');
            if (!in_array($slug, $this->anchors)) {
                $this->anchors[] = $slug;
                return $slug;
            }
        }

        throw new \Exception('Unable to generate a slug after 1000 attempts');
    }
}
