<?php

namespace Vulcan\UserDocs\Tests\Parsers;

use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
use SilverStripe\View\Parsers\ShortcodeParser;
use Sunra\PhpSimple\HtmlDomParser;
use Vulcan\UserDocs\Models\CodeTab;
use Vulcan\UserDocs\Parsers\Shortcode;

class ShortcodeTest extends FunctionalTest
{
    protected static $fixture_file = [
        __DIR__ . '/../Fixtures/MemberFixture.yml',
        'ShortcodeTest.yml'
    ];

    public function testRegisterShortcodes()
    {
        $allMethods = get_class_methods(Shortcode::class);
        Shortcode::registerShortcodes();
        $registered = ShortcodeParser::get()->getRegisteredShortcodes();

        foreach ($allMethods as $method) {
            // ignore inherited methods
            if (in_array($method, ['registerShortcodes', 'config', 'create', 'singleton', 'stat', 'uninherited', 'set_stat'])) {
                continue;
            }

            $error = sprintf("Failed to find %s registered as a shortcode", $method);
            $this->assertArrayHasKey($method, $registered, $error);
        }
    }

    /**
     * @covers \Vulcan\UserDocs\Parsers\Shortcode::logged_in()
     */
    public function testLoggedIn()
    {
        $result = ShortcodeParser::get_active()->parse("[logged_in]You should not be seeing this message[/logged_in]");
        $this->assertEquals("", $result);

        /** @var Member $member */
        $member = $this->objFromFixture(Member::class, 'john_doe');
        Security::setCurrentUser($member);

        $result = ShortcodeParser::get_active()->parse("[logged_in]You should be seeing this message[/logged_in]");
        $this->assertEquals('You should be seeing this message', $result);
    }

    /**
     * @covers \Vulcan\UserDocs\Parsers\Shortcode::not_logged_in()
     */
    public function testNotLoggedIn()
    {
        $result = ShortcodeParser::get_active()->parse("[not_logged_in]You should be seeing this message[/not_logged_in]");
        $this->assertEquals('You should be seeing this message', $result);

        /** @var Member $member */
        $member = $this->objFromFixture(Member::class, 'john_doe');
        Security::setCurrentUser($member);

        $result = ShortcodeParser::get_active()->parse("[not_logged_in]You should NOT be seeing this message[/not_logged_in]");
        $this->assertEquals('', $result);
    }

    /**
     * @covers \Vulcan\UserDocs\Parsers\Shortcode::code()
     */
    public function testCode()
    {
        $result = ShortcodeParser::get_active()->parse('[code]Hello World![/code]');
        $parser = HtmlDomParser::str_get_html($result);

        $this->assertContains('Hello World!', (string)$parser);

        $span = $parser->find('span');

        $this->assertNotEmpty($span, 'A span tag was expected in the result');
        $this->assertCount(1, $span, 'Only one span tag should exist in the result');
        $classes = (isset($span[0])) ? $span[0]->getAttribute('class') : null;
        $this->assertContains('userdocs-code', $classes ?: '', 'The "userdocs-code" class is missing from the result');

        $result = ShortcodeParser::get_active()->parse('[code,display="block",lang="plaintext"]Hello World![/code]');
        $parser = HtmlDomParser::str_get_html($result);

        $divTag = $parser->find('div');
        $this->assertNotEmpty($divTag, 'A div tag was expected in the result');
        $this->assertCount(1, $divTag, 'Only one div tag should exist in the result');
        $classes = (isset($divTag[0])) ? $divTag[0]->getAttribute('class') : null;
        $this->assertContains('userdocs-codeblock', $classes ?: '', 'The "userdocs-codeblock" class is missing from the result');
        $this->assertNull((isset($divTag[0])) ? $divTag[0]->parent()->parent() : false, 'The only div tag that is expected in the result should be the top most parent');
        $result = ShortcodeParser::get_active()->parse('[code]       [/code]');
        $this->assertEquals('', $result);
    }

    /**
     * @covers \Vulcan\UserDocs\Parsers\Shortcode::codetab()
     */
    public function testCodeTab()
    {
        /** @var CodeTab $tab */
        $tab = $this->objFromFixture(CodeTab::class, 'first');
        $parser = HtmlDomParser::str_get_html(ShortcodeParser::get_active()->parse('[codetab,id="hello-world",page_id="1002"]'));
        $this->assertCount(4, $parser->find('div.userdocs-codetab'), 'Four div elements with a class name of "userdocs-codetab" was expected');
        $this->assertNotNull($parser->find('.request-parameters', 0), 'A div with a class name of "userdocs-codetab" and "request-parameters" was expected');
        $this->assertNotNull($parser->find('.response-parameters', 0), 'A div with a class name of "userdocs-codetab" and "response-parameters" was expected');
        $this->assertNotNull($parser->find('.response', 0), 'A div with a class name of "userdocs-codetab" and "response" was expected');

        $this->assertNotContains('[code]bar[/code]', 'Non-parsed shortcodes: should invoke ShortcodeParser::get_active()->parse(..)');

        // A slug/id has not been specified, this should throw an exception
        $result = ShortcodeParser::get_active()->parse('[codetab,id="doesnt-exist",page_id="1002"]');
        $this->assertEquals('[codetab: id/slug was not found as a registered codetab for the active page]', $result);

        $result = ShortcodeParser::get_active()->parse('[codetab,page_id="1002"]');
        $this->assertEquals('[codetab: No id/slug was supplied]', $result);

        // A slug/id has not been specified, this should throw an exception
        $this->expectException(\Exception::class);
        ShortcodeParser::get_active()->parse('[codetab]');

        // no active controller, so the page_id cannot be automatically detected
        $this->expectException(\Exception::class);
        ShortcodeParser::get_active()->parse('[codetab,id="hello-world"]');
    }
}
