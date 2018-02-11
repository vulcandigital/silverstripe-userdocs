<?php

namespace Vulcan\UserDocs\Tests;

use SilverStripe\Dev\FunctionalTest;
use Vulcan\UserDocs\Models\CodeTab;
use Vulcan\UserDocs\Models\CodeTabResponseParameter;

class CodeTabResponseParameterTest extends FunctionalTest
{
    protected static $fixture_file = 'CodeTabResponseParameterTest.yml';

    /** @var CodeTab */
    protected $codeTab;

    public function setUp()
    {
        parent::setUp();

        $this->codeTab = $this->objFromFixture(CodeTab::class, 'first');
    }

    public function testTableName()
    {
        $this->assertEquals('CodeTabResponseParameter', CodeTabResponseParameter::config()->get('table_name'));
    }
}
