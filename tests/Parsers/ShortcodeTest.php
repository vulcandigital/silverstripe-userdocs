<?php

namespace Vulcan\UserDocs\Tests;

use SilverStripe\Dev\FunctionalTest;
use SilverStripe\View\Parsers\ShortcodeParser;
use Vulcan\UserDocs\Parsers\Shortcode;

class ShortcodeTest extends FunctionalTest
{
    public function testRegisterShortcodes()
    {
        $allMethods = get_class_methods(Shortcode::class);
        Shortcode::registerShortcodes();
        $registered = array_keys(ShortcodeParser::get()->getRegisteredShortcodes());

        foreach ($allMethods as $method) {
            // ignore inherited methods
            if (in_array($method, ['registerShortcodes', 'config', 'create', 'singleton', 'stat', 'uninherited', 'set_stat'])) {
                continue;
            }

            $this->assertArrayHasKey($method, $registered, "Failed to find %s registered as a shortcode");
        }
    }
}