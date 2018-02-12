<?php

namespace Vulcan\UserDocs\Pages;

use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;

/**
 * Class UserDocsController
 * @package Vulcan\UserDocs\Pages
 *
 * @method string getAnchoredContent($content = null, $link = null)
 */
class UserDocsController extends \PageController
{
    private static $allowed_actions = [];

    /**
     * @config
     * @var bool
     */
    private static $require_css = true;

    /**
     * @config
     * @var bool
     */
    private static $require_js = true;

    /**
     * @config
     * @var string
     */
    private static $highlight_theme = 'monokai-sublime';

    /**
     * @config
     * @var string
     */
    private static $highlight_version = '9.12.0';

    /**
     * Use this method to add the default CSS and JS. This will not be added automatically and you should add it
     * in the correct position before requiring your main stylesheet and JS files so that those files have access to
     * override CSS and call JS functionality if required
     *
     * @return void
     */
    public static function addResourceRequirements()
    {
        $theme = static::config()->get('highlight_theme');
        $highlightVer = static::config()->get('highlight_version');
        $highlightCss = "https://cdnjs.cloudflare.com/ajax/libs/highlight.js/$highlightVer/styles/$theme.min.css";

        if (static::config()->get('require_js')) {
            Requirements::javascript("https://cdnjs.cloudflare.com/ajax/libs/highlight.js/$highlightVer/highlight.min.js");
            Requirements::javascript('vulcandigital/silverstripe-userdocs:js/main.min.js');
        }

        if (static::config()->get('require_css')) {
            Requirements::css($highlightCss);
            Requirements::css('vulcandigital/silverstripe-userdocs:css/main.min.css');
        }
    }
}
