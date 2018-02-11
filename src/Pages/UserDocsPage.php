<?php

namespace Vulcan\UserDocs\Pages;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\HasManyList;
use Vulcan\UserDocs\Models\CodeTab;
use Vulcan\UserDocs\Traits\ParseHeadingsAsAnchors;

/**
 * Class UserDocsPage
 * @package Vulcan\UserDocs\Pages
 *
 * @method HasManyList|CodeTab[] CodeTabs
 */
class UserDocsPage extends \Page
{
    private static $table_name = 'UserDocsPage';

    private static $can_be_root = false;

    /**
     * No page is allowed as a children to this page
     *
     * @var array
     */
    private static $allowed_children = [];

    private static $has_many = [
        'CodeTabs' => CodeTab::class
    ];

    private static $cascade_deletes = [
        'CodeTabs'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.CodeTabs', [
            GridField::create('CodeTabs', 'CodeTabs', $this->CodeTabs(), GridFieldConfig_RecordEditor::create())
        ]);

        return $fields;
    }
}
