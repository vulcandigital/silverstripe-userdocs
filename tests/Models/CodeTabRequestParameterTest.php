<?php

namespace Vulcan\UserDocs\Tests;

use SilverStripe\Dev\FunctionalTest;
use Vulcan\UserDocs\Models\CodeTab;
use Vulcan\UserDocs\Models\CodeTabRequestParameter;

class CodeTabRequestParameterTest extends FunctionalTest
{
    protected static $fixture_file = 'CodeTabRequestParameterTest.yml';

    /** @var CodeTab */
    protected $codeTab;

    public function setUp()
    {
        parent::setUp();

        $this->codeTab = $this->objFromFixture(CodeTab::class, 'first');
    }

    public function testTableName()
    {
        $this->assertEquals('CodeTabRequestParameter', CodeTabRequestParameter::config()->get('table_name'));
    }
}
