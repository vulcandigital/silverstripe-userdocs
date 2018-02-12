<?php

namespace Vulcan\UserDocs\Models;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\HasManyList;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Parsers\URLSegmentFilter;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use Vulcan\UserDocs\Pages\UserDocsPage;

/**
 * Class CodeTab
 * @package Vulcan\UserDocs\Models
 *
 * @property string Title
 * @property string Slug             A slug is required to include this codetab into a pages content via shortcode, a unique slug
 *                                   will automatically be generated if not provided
 * @property string RequestMethod    One of GET, POST, PUT, DELETE or PATCH
 * @property string Response         An example response.
 * @property string ResponseLanguage What language the response should be highlighted in, usually JSON or XML
 *
 * @property int    PageID
 *
 * @method HasManyList|CodeTabExample[] Examples
 * @method HasManyList|CodeTabRequestParameter[] RequestParameters
 * @method HasManyList|CodeTabResponseParameter[] ResponseParameters
 */
class CodeTab extends DataObject
{
    private static $table_name = 'CodeTab';

    private static $db = [
        'Title'            => 'Varchar(255)',
        'RequestMethod'    => 'Varchar(6)',
        'Slug'             => 'Varchar(255)',
        'Response'         => 'Text',
        'ResponseLanguage' => 'Varchar(10)'
    ];

    private static $has_one = [
        'Page' => UserDocsPage::class
    ];

    private static $has_many = [
        'Examples'           => CodeTabExample::class,
        'RequestParameters'  => CodeTabRequestParameter::class,
        'ResponseParameters' => CodeTabResponseParameter::class
    ];

    private static $summary_fields = [
        'Title'             => 'Title',
        'Slug'              => 'Slug',
        'RequestMethod'     => 'Method',
        'LanguagesAsString' => 'Examples'
    ];

    public function validate()
    {
        $result = parent::validate();

        if (!$this->PageID) {
            $result->addError('A parent page must be provided');
        }

        if ($this->Response && !$this->ResponseLanguage) {
            $result->addFieldError('ResponseLanguage', 'If you provide a response, you must also specify the response language');
        }

        if ($this->ResponseLanguage && !$this->Response) {
            $result->addFieldError('Response', 'If you specify a response language, you must also provide the response');
        }

        return $result;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['Page', 'RequestParameters', 'ResponseParameters', 'Sort']);

        $fields->replaceField('Slug', $this->dbObject('Slug')->scaffoldFormField()->performReadonlyTransformation());
        $fields->replaceField('Response', $this->dbObject('Response')->scaffoldFormField()->setTitle('Example Response')->setRightTitle('If you are documenting an API, it can be useful to provide an example of what the provided code examples respond with'));
        $fields->replaceField('RequestMethod', DropdownField::create('RequestMethod', 'Request Method', static::getHttpRequestMethods()->toMap())->setHasEmptyDefault(true)->setEmptyString('Please select...'));
        $fields->replaceField('ResponseLanguage', DropdownField::create('ResponseLanguage', 'Response Language', static::getResponseLanguageMap()->toMap())->setHasEmptyDefault(true)->setEmptyString('Please select...'));

        $fields->addFieldsToTab('Root.RequestParameters', [
            GridField::create('RequestParameters', 'Request Parameters', $this->RequestParameters(), GridFieldConfig_RecordEditor::create()->addComponent(new GridFieldOrderableRows()))
        ]);

        $fields->addFieldsToTab('Root.ResponseParameters', [
            GridField::create('ResponseParameters', 'Response Parameters', $this->ResponseParameters(), GridFieldConfig_RecordEditor::create()->addComponent(new GridFieldOrderableRows()))
        ]);

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->Slug || ((isset($this->original['Title']) && $this->original['Title'] != $this->Title))) {
            $this->Slug = $this->generateSlug();
        }
    }

    /**
     * Generate a slug for this CodeTab
     *
     * @return string
     * @throws \Exception
     */
    private function generateSlug()
    {
        $filter = URLSegmentFilter::create();
        $this->Slug = $filter->filter($this->Title);
        $count = 2;
        while (!$this->isSlugValid()) {
            $this->Slug = $filter->filter("{$this->Title} $count");
            $count++;
        }

        return $this->Slug;
    }

    /**
     * Checks to see if the slug is valid by making sure another record, on the same page does not have the same slug
     *
     * @return bool
     */
    private function isSlugValid()
    {
        $record = static::get()->filter(['Slug' => $this->Slug, 'PageID' => $this->PageID, 'ID:not' => $this->ID])->first();

        return ($record) ? false : true;
    }

    /**
     * Return a map of the available HTTP request methods
     *
     * @return ArrayData
     */
    public static function getHttpRequestMethods()
    {
        return ArrayData::create([
            'GET'    => 'GET',
            'POST'   => 'POST',
            'PUT'    => 'PUT',
            'DELETE' => 'DELETE',
            'PATCH'  => 'PATCH'
        ]);
    }

    /**
     * Return example languages as a comma (, ) separated list
     *
     * @return string
     */
    public function getLanguagesAsString()
    {
        $output = [];

        foreach ($this->Examples() as $example) {
            $output[] = $example->getReadableLanguage();
        }

        return implode(', ', $output);
    }

    /**
     * Return a map of the available response languages
     *
     * @return ArrayData
     */
    public static function getResponseLanguageMap()
    {
        return ArrayData::create([
            'json' => 'JSON',
            'html' => 'XML'
        ]);
    }

    /**
     * Get the readable variant of the response language alias. ie cpp => C++
     *
     * @return string|null
     */
    public function getReadableResponseLanguage()
    {
        return static::getResponseLanguageMap()->{$this->ResponseLanguage};
    }

    /**
     * @return mixed|string
     */
    public function forTemplate()
    {
        return $this->renderWith('Vulcan\UserDocs\Parsers\Shortcode\CodeTab')->forTemplate();
    }
}
