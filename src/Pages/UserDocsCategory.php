<?php

namespace Vulcan\UserDocs\Pages;

/**
 * Class UserDocsCategory
 * @package Vulcan\UserDocs\Pages
 */
class UserDocsCategory extends \Page
{
    private static $table_name = 'UserDocsCategory';

    private static $can_be_root = false;

    private static $allowed_children = [
        self::class,
        UserDocsPage::class
    ];
}
