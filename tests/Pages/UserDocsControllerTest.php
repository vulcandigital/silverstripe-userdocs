<?php

namespace Vulcan\UserDocs\Tests\Pages;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\View\Requirements;
use SilverStripe\View\ThemeResourceLoader;
use Vulcan\UserDocs\Pages\UserDocsController;

class UserDocsControllerTest extends FunctionalTest
{
    public function testAddRequiredResources()
    {
        $highlightVer = "1.33.7";
        $highlightTheme = 'vulcandigital';

        Config::modify()->remove(UserDocsController::class, 'highlight_version');
        Config::modify()->remove(UserDocsController::class, 'highlight_theme');
        Config::modify()->set(UserDocsController::class, 'highlight_version', $highlightVer);
        Config::modify()->set(UserDocsController::class, 'highlight_theme', $highlightTheme);

        UserDocsController::addResourceRequirements();
        $cssMap = Requirements::backend()->getCSS();
        $jsMap = Requirements::backend()->getJavascript();

        $highlightJsUrl = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/1.33.7/highlight.min.js';
        $mainJsUrl = 'vulcandigital/silverstripe-userdocs:js/main.min.js';
        $highlightCssUrl = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/1.33.7/styles/vulcandigital.min.css';
        $mainCssUrl = 'vulcandigital/silverstripe-userdocs:css/main.min.css';

        $this->assertArrayHasKey($highlightCssUrl, $cssMap);
        $this->assertArrayHasKey(ModuleResourceLoader::resourcePath($mainCssUrl), $cssMap);
        $this->assertArrayHasKey($highlightJsUrl, $jsMap);
        $this->assertArrayHasKey(ModuleResourceLoader::resourcePath($mainJsUrl), $jsMap);

        Config::modify()->remove(UserDocsController::class, 'require_css');
        Config::modify()->remove(UserDocsController::class, 'require_js');
        Config::modify()->set(UserDocsController::class, 'require_css', false);
        Config::modify()->set(UserDocsController::class, 'require_js', false);

        Requirements::clear();
        UserDocsController::addResourceRequirements();
        $cssMap = Requirements::backend()->getCSS();
        $jsMap = Requirements::backend()->getJavascript();

        $this->assertArrayNotHasKey($highlightCssUrl, $cssMap, 'File should not be required when $require_css is false');
        $this->assertArrayNotHasKey(ModuleResourceLoader::resourcePath($mainCssUrl), $cssMap, 'File should not be required when $require_css is false');
        $this->assertArrayNotHasKey($highlightJsUrl, $jsMap, 'File should not be required when $require_js is false');
        $this->assertArrayNotHasKey(ModuleResourceLoader::resourcePath($mainJsUrl), $jsMap, 'File should not be required when $require_js is false');
    }
}
