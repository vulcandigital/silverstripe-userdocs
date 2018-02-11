<?php

namespace Vulcan\UserDocs\Tests\Pages;

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Session;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\FunctionalTest;
use Vulcan\UserDocs\Pages\UserDocs;
use Vulcan\UserDocs\Pages\UserDocsController;

class UserDocsTest extends FunctionalTest
{
    protected static $fixture_file = 'UserDocsTest.yml';

    /** @var UserDocs */
    protected $page;

    /** @var  UserDocsController */
    protected $controller;

    public function setUp()
    {
        parent::setUp();

        $this->page = $this->objFromFixture(UserDocs::class, 'first');
        $this->page->publishSingle();

        $this->controller = new UserDocsController($this->page);
    }

    public function testTableName()
    {
        $this->assertEquals('UserDocs', UserDocs::config()->get('table_name'), '$table_name should be UserDocs');
    }
}
