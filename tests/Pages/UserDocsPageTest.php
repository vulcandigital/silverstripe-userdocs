<?php

namespace Vulcan\UserDocs\Tests\Pages;

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Session;
use SilverStripe\Dev\FunctionalTest;
use Sunra\PhpSimple\HtmlDomParser;
use Vulcan\UserDocs\Pages\UserDocs;
use Vulcan\UserDocs\Pages\UserDocsPage;
use Vulcan\UserDocs\Pages\UserDocsPageController;

class UserDocsPageTest extends FunctionalTest
{
    protected static $fixture_file = 'UserDocsPageTest.yml';

    /** @var  UserDocs */
    protected $rootPage;

    public function setUp()
    {
        parent::setUp();

        $this->rootPage = $this->objFromFixture(UserDocs::class, 'first');
        $this->rootPage->publishSingle();
    }

    public function testParsedContent()
    {
        /** @var UserDocsPage $page */
        $page = $this->objFromFixture(UserDocsPage::class, 'first');
        $page->publishSingle();

        /** @var UserDocsPageController $controller */
        $controller = new UserDocsPageController($page);
        $content = $controller->getAnchoredContent(null, '/docs/getting-started/');
        $parser = HtmlDomParser::str_get_html($content);

        $this->assertFalse($page->config()->get('can_be_root'), '$can_be_root should be false');
        $this->assertEquals('UserDocsPage', $page->config()->get('table_name'), '$table_name should be UserDocsPage');

        $this->assertNotNull($links = $parser->find('a'), 'Anchor links should have been generated from the content');
        $this->assertCount(5, $links, '5 anchor links should have been generated from the content');

        $link1 = (isset($links[0])) ? $links[0]->getAttribute('href') : null;
        $link2 = (isset($links[1])) ? $links[1]->getAttribute('href') : null;
        $link3 = (isset($links[2])) ? $links[2]->getAttribute('href') : null;
        $link4 = (isset($links[3])) ? $links[3]->getAttribute('href') : null;
        $link5 = (isset($links[4])) ? $links[4]->getAttribute('href') : null;
        $anchorError = 'The anchor link was not generated correctly';
        $anchorAppendageError = 'The anchor link was not generated correctly, the number 1 should not be appended to the slug';
        $this->assertEquals('/docs/getting-started/#we-need', $link1, $anchorError);
        $this->assertEquals('/docs/getting-started/#to-ensure', $link2, $anchorError);
        $this->assertEquals('/docs/getting-started/#to-ensure-2', $link3, $anchorError);
        $this->assertEquals('/docs/getting-started/#that-all-headings', $link4, $anchorError);
        $this->assertEquals('/docs/getting-started/#convert-to-anchor-links', $link5, $anchorError);
        $this->assertNotEquals('/docs/getting-started/#we-need-1', $link1, $anchorAppendageError);
        $this->assertNotEquals('/docs/getting-started/#to-ensure-1', $link2, $anchorAppendageError);
        $this->assertNotEquals('/docs/getting-started/#that-all-headings-1', $link4, $anchorAppendageError);
        $this->assertNotEquals('/docs/getting-started/#convert-to-anchor-links-1', $link5, $anchorAppendageError);
    }

    /**
     * Mock a request against a given controller
     *
     * @param ContentController $controller
     * @param string            $url
     *
     * @return \SilverStripe\Control\HTTPResponse
     */
    protected function mockRequestURL(ContentController $controller, $url)
    {
        $request = new HTTPRequest('get', $url);
        $request->match('$URLSegment//$Action/$ID/$OtherID');
        $request->shift();
        $session = new Session(null);
        $session->start($request);
        $request->setSession($session);
        $controller->doInit();
        $response = $controller->handleRequest($request);
        $session->clearAll();
        $session->destroy();

        return $response;
    }
}
