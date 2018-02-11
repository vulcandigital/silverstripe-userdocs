<?php

namespace Vulcan\UserDocs\Pages;

use Vulcan\UserDocs\Traits\ParseHeadingsAsAnchors;

class UserDocsPageController extends \PageController
{
    use ParseHeadingsAsAnchors;

    private static $allowed_actions = [];
}
