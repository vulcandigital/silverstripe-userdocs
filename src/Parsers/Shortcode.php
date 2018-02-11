<?php

namespace Vulcan\UserDocs\Parsers;

use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Security\Security;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Parsers\ShortcodeParser;
use Vulcan\UserDocs\Models\CodeTab;

class Shortcode
{
    use Injectable, Configurable;

    /**
     * Dynamically register all methods in this class as a shortcode. The method name will become the shortcode, for example
     * a function with the name of "code" will be registered as [code][/code]
     *
     * @return void;
     */
    public static function registerShortcodes()
    {
        $methods = get_class_methods(self::class);

        foreach ($methods as $method) {
            if (in_array($method, [__FUNCTION__, 'config', 'create', 'singleton', 'stat', 'uninherited', 'set_stat'])) {
                continue;
            }

            ShortcodeParser::get('default')->register($method, [static::class, $method]);
        }
    }

    /**
     * @param array                $arguments
     * @param null|string          $content
     * @param null|ShortcodeParser $parser
     * @param string               $tagName
     *
     * @return \SilverStripe\ORM\FieldType\DBHTMLText|string
     */
    public static function code($arguments, $content = null, $parser = null, $tagName)
    {
        if (!isset($arguments['style']) || $arguments['style'] == 'inline') {
            return ArrayData::create(['Content' => $content])->renderWith('Vulcan\UserDocs\Parsers\Shortcode\CodeInline');
        }

        return $parser->parse(ArrayData::create(['Content' => $content, 'Language' => $arguments['lang'] ?: null])->renderWith('Vulcan\UserDocs\Parsers\Shortcode\CodeBlock'));
    }

    /**
     * @param array                $arguments
     * @param null|string          $content
     * @param null|ShortcodeParser $parser
     * @param string               $tagName
     *
     * @return \SilverStripe\ORM\FieldType\DBHTMLText|string
     */
    public static function codetab($arguments, $content = null, $parser = null, $tagName)
    {
        $pageId = (isset($arguments['page_id'])) ? $arguments['page_id'] : Controller::curr()->ID;

        if (!$pageId) {
            user_error('No page_id was supplied nor found in the active controller', E_USER_ERROR);
        }

        if (!isset($arguments['id'])) {
            user_error('No id was supplied', E_USER_ERROR);
        }

        $record = CodeTab::get()->filter('PageID', $pageId)->filterAny(['ID' => $arguments['id'], 'Slug' => $arguments['id']])->first();

        if (!$record) {
            user_error(sprintf('Code identifier %s was not found as a registered tab for the active page'), E_USER_ERROR);
        }

        return $parser->parse($record->renderWith('Vulcan\UserDocs\Parsers\Shortcode\CodeTab'));
    }

    /**
     * Only show contents of shortcode if the user is currently logged in
     *
     * @param array                $arguments
     * @param null|string          $content
     * @param null|ShortcodeParser $parser
     * @param string               $tagName
     *
     * @return \SilverStripe\ORM\FieldType\DBHTMLText|string
     */
    public static function logged_in($arguments, $content = null, $parser = null, $tagName)
    {
        if (!$me = Security::getCurrentUser()) {
            return '';
        }

        return $parser->parse($content);
    }

    /**
     * Only show contents of shortcode if the user is not currently logged in
     *
     * @param array                $arguments
     * @param null|string          $content
     * @param null|ShortcodeParser $parser
     * @param string               $tagName
     *
     * @return \SilverStripe\ORM\FieldType\DBHTMLText|string
     */
    public static function not_logged_in($arguments, $content = null, $parser = null, $tagName)
    {
        if ($me = Security::getCurrentUser()) {
            return '';
        }

        return $parser->parse($content);
    }
}