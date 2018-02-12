<?php

namespace Vulcan\UserDocs\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Parsers\ShortcodeParser;
use SilverStripe\View\Parsers\URLSegmentFilter;
use Sunra\PhpSimple\HtmlDomParser;
use Vulcan\UserDocs\Pages\UserDocs;
use Vulcan\UserDocs\Pages\UserDocsCategory;
use Vulcan\UserDocs\Pages\UserDocsPage;

/**
 * Class PageControllerExtension
 * @package Vulcan\UserDocs\Extensions
 *
 * @property \Page owner
 */
class PageControllerExtension extends Extension
{
    /** @var array Unique slug storage to ensure there are no duplicates */
    private $anchors = [];

    /**
     * Helper to determine if the current page is apart of this module
     *
     * @param null $className
     *
     * @return bool
     */
    public function IsUserDocsPage($className = null)
    {
        $className = ($className) ? $className : $this->owner->ClassName;

        return in_array($className, [
            UserDocs::class,
            UserDocsCategory::class,
            UserDocsPage::class
        ]);
    }

    /**
     * @param null $currentPage
     * @param null $output
     *
     * @return bool|ArrayList
     */
    public function generateUserDocsBreadcrumb($currentPage = null, $output = null)
    {
        if (!$this->IsUserDocsPage()) {
            return false;
        }

        $output = ($output) ? $output : ArrayList::create([]);
        $currentPage = ($currentPage) ? $currentPage : $this->owner;
        $output->unshift([
            'Title' => $currentPage->MenuTitle,
            'Link'  => $currentPage->Link()
        ]);

        if ($currentPage->Parent()->exists()) {
            $this->generateUserDocsBreadcrumb($currentPage->Parent(), $output);
        }

        if ($output->count() === 1) {
            return false;
        }

        return $output;
    }

    /**
     * @return DBHTMLText|bool
     */
    public function UserDocsBreadcrumb()
    {
        if (!$this->IsUserDocsPage()) {
            return false;
        }

        return ArrayData::create([
            'Breadcrumbs' => $this->generateUserDocsBreadcrumb()
        ])->renderWith('Vulcan\UserDocs\Pages\Includes\Breadcrumb');
    }

    /**
     * Render the contents of this category. You should always append ".RAW" when using
     * this method in a template e.g $RenderContents.RAW
     *
     * @return DBHTMLText|bool
     */
    public function UserDocsContents()
    {
        if (!$this->IsUserDocsPage()) {
            return false;
        }

        $output = $this->getAnchoredContent(ArrayData::create(['Parent' => $this->owner])->renderWith('Vulcan\UserDocs\Pages\Includes\Contents'));

        return DBHTMLText::create()->setValue($output);
    }

    /**
     * @param null|string $content Optionally force other content into this method to be parsed
     *
     * @param null|string $link    An optional base url for the anchor,  by default it will attempt $this->Link()
     *
     * @return string
     */
    public function getAnchoredContent($content = null, $link = null)
    {
        $content = ($content) ?: $this->owner->Content;
        $link = $link ?: $this->owner->Link();

        $parser = HtmlDomParser::str_get_html((string)$content);
        if (!$parser) {
            return $content;
        }
        $headings = $parser->find('h1,h2,h3,h4,h5');
        if ($headings) {
            foreach ($headings as $heading) {
                $text = $heading->innertext();
                $slug = $this->generateAnchorSlug($text);
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
    public function generateAnchorSlug($heading)
    {
        $filter = URLSegmentFilter::create();
        $slug = $filter->filter($heading);
        $count = 2;
        while (!$this->isValidAnchorSlug($slug)) {
            $slug = $filter->filter($heading) . "-$count";
        }

        $this->anchors[] = $slug;
        return $slug;
    }

    /**
     * @param $slug
     *
     * @return bool
     */
    public function isValidAnchorSlug($slug)
    {
        return (!in_array($slug, $this->anchors));
    }
}
