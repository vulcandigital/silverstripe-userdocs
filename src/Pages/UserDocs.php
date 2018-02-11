<?php

namespace Vulcan\UserDocs\Pages;

class UserDocs extends \Page
{
    private static $table_name = 'UserDocs';

    private static $allowed_children = [
        UserDocsCategory::class,
        UserDocsPage::class
    ];
}
