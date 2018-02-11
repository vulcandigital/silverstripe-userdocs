<?php

namespace Vulcan\UserDocs\Tests;

use SilverStripe\Dev\FunctionalTest;
use SilverStripe\View\ArrayData;
use Vulcan\UserDocs\Models\CodeTab;

class CodeTabTest extends FunctionalTest
{
    protected static $fixture_file = 'CodeTabTest.yml';

    /** @var CodeTab */
    protected $codeTab;

    /** @var CodeTab */
    protected $otherCodeTab;

    public function setUp()
    {
        parent::setUp();

        $this->codeTab = $this->objFromFixture(CodeTab::class, 'first');
        $this->otherCodeTab = $this->objFromFixture(CodeTab::class, 'second');
    }

    public function testTableName()
    {
        $this->assertEquals('CodeTab', CodeTab::config()->get('table_name'));
    }

    public function testGenerateSlug()
    {
        $this->assertEquals('hello-world', $this->codeTab->Slug);
        $this->assertEquals('hello-world-2', $this->otherCodeTab->Slug);
    }

    public function testHttpRequestMethods()
    {
        $expected = [
            'GET'    => 'GET',
            'POST'   => 'POST',
            'PUT'    => 'PUT',
            'DELETE' => 'DELETE',
            'PATCH'  => 'PATCH'
        ];
        $data = CodeTab::getHttpRequestMethods();
        ksort($expected);
        $actual = $data->toMap();
        ksort($actual);

        $this->assertInstanceOf(ArrayData::class, $data);
        $this->assertEquals($expected, $actual);
    }

    public function testLanguagesAsString()
    {
        $this->assertEquals('PHP, Bash', $this->codeTab->getLanguagesAsString());
    }

    public function testResponseLanguageMap()
    {
        $data = CodeTab::getResponseLanguageMap();
        $this->assertInstanceOf(ArrayData::class, $data);
        $this->assertEquals([
            'json' => 'JSON',
            'html'  => 'XML'
        ], $data->toMap());
    }

    public function getReadableResponseLanguage()
    {
        $this->assertEquals('JSON', $this->codeTab->ResponseLanguage);
        $this->assertEquals('XML', $this->otherCodeTab->ResponseLanguage);
    }
}
