<?php

namespace Vulcan\UserDocs\Pages;

use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;
use Vulcan\UserDocs\Traits\ParseHeadingsAsAnchors;

class UserDocsController extends \PageController
{
    use ParseHeadingsAsAnchors;

    private static $allowed_actions = [];

    private static $require_css = true;

    private static $require_js = true;

    private static $highlight_theme = 'monokai-sublime';

    /**
     * Render the contents of this category. You should always append ".RAW" when using
     * this method in a template e.g $RenderContents.RAW
     *
     * @return DBHTMLText
     */
    public function RenderContents()
    {
        $output = $this->getAnchoredContent(ArrayData::create(['Parent' => $this])->renderWith('Vulcan\UserDocs\Pages\Includes\Contents'));

        return DBHTMLText::create()->setValue($output);
    }

    /**
     * Use this method to add the default CSS and JS. This will not be added automatically and you should add it
     * in the correct position before requiring your main stylesheet and JS files so that those files have access to
     * override CSS and call JS functionality if required
     */
    public static function addResourceRequirements()
    {
        $highlightCss = "https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/%s.min.css";
        $theme = static::config()->get('highlight_theme');

        if (static::config()->get('require_js')) {
            Requirements::javascript('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js');
            Requirements::css(sprintf($highlightCss, $theme));
            Requirements::javascript('vulcandigital/silverstripe-userdocs:js/main.min.js');
        }

        if (static::config()->get('require_css')) {
            Requirements::css('vulcandigital/silverstripe-userdocs:css/main.min.css');
        }
    }
}
