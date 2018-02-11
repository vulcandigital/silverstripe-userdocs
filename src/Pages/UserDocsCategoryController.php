<?php

namespace Vulcan\UserDocs\Pages;

use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\ArrayData;
use Vulcan\UserDocs\Traits\ParseHeadingsAsAnchors;

/**
 * Class UserDocsCategoryController
 * @package Vulcan\UserDocs\Pages
 */
class UserDocsCategoryController extends \PageController
{
    use ParseHeadingsAsAnchors;

    private static $allowed_actions = [];

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
}
