<?php

namespace Vulcan\UserDocs\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use Vulcan\UserDocs\Pages\UserDocs;
use Vulcan\UserDocs\Pages\UserDocsCategory;
use Vulcan\UserDocs\Pages\UserDocsPage;

/**
 * Class PageControllerExtension
 * @package Vulcan\UserDocs\Extensions
 *
 * @property \Page owner
 */
class PageControllerExtension extends Extension
{
    /**
     * Helper to determine if the current page is apart of this module
     *
     * @return bool
     */
    public function IsUserDocsPage($className = null)
    {
        $className = ($className) ? $className : $this->owner->ClassName;

        return in_array($className, [
            UserDocs::class,
            UserDocsCategory::class,
            UserDocsPage::class
        ]);
    }

    public function generateUserDocsBreadcrumb($currentPage = null, $output = null)
    {
        if (!$this->IsUserDocsPage()) {
            return false;
        }

        $output = ($output) ? $output : ArrayList::create([]);
        $currentPage = ($currentPage) ? $currentPage : $this->owner;
        $output->unshift([
            'Title' => $currentPage->MenuTitle,
            'Link'  => $currentPage->Link()
        ]);

        if ($currentPage->Parent()->exists()) {
            $this->generateUserDocsBreadcrumb($currentPage->Parent(), $output);
        }

        if ($output->count() === 1) {
            return false;
        }

        return $output;
    }

    public function UserDocsBreadcrumb()
    {
        return ArrayData::create(['Breadcrumbs' => $this->generateUserDocsBreadcrumb()])->renderWith('Vulcan\UserDocs\Pages\Includes\Breadcrumb');
    }
}