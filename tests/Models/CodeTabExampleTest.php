<?php

namespace Vulcan\UserDocs\Tests;

use SilverStripe\Dev\FunctionalTest;
use Vulcan\UserDocs\Models\CodeTab;
use Vulcan\UserDocs\Models\CodeTabExample;

class CodeTabExampleTest extends FunctionalTest
{
    protected static $fixture_file = 'CodeTabExampleTest.yml';

    /** @var CodeTab */
    protected $codeTab;

    public function setUp()
    {
        parent::setUp();

        $this->codeTab = $this->objFromFixture(CodeTab::class, 'first');
    }

    public function testTableName()
    {
        $this->assertEquals('CodeTabExample', CodeTabExample::config()->get('table_name'));
    }
}
