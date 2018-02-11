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

    public function validate()
    {
        $result = parent::validate();

        if ($this->Response && !$this->ResponseLanguage) {
            $result->addFieldError('ResponseLanguage', 'If you provide a response, you must also specify the response language');
        }

        if ($this->ResponseLanguage && !$this->Response) {
            $result->addFieldError('Response', 'If you specify a response language, you must also provide the response');
        }

        return $result;
    }

    private static $summary_fields = [
        'Title'             => 'Title',
        'Slug'              => 'Slug',
        'RequestMethod'     => 'Method',
        'LanguagesAsString' => 'Examples'
    ];

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

        if (!$this->Slug || $this->original['Title'] != $this->Title) {
            $this->Slug = $this->generateSlug();
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateSlug()
    {
        $filter = URLSegmentFilter::create();
        $slug = $filter->filter($this->Title);
        for ($i = 1; $i < 1000; $i++) {
            $exists = static::get()->filter(['Slug' => $slug, 'PageID' => $this->PageID, 'ID:not' => $this->ID])->first();

            if ($exists) {
                $slug = $filter->filter("{$this->Title} $i");
                continue;
            }

            return $slug;
        }

        throw new \Exception('A slug could not be generated for this tab after 1000 attempts');
    }

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

    public function getLanguagesAsString()
    {
        $output = [];

        foreach ($this->Examples() as $example) {
            $output[] = $example->getReadableLanguage();
        }

        return implode(', ', $output);
    }

    public static function getResponseLanguageMap()
    {
        return ArrayData::create([
            'json' => 'JSON',
            'xml'  => 'XML'
        ]);
    }

    public function getReadableResponseLanguage()
    {
        return static::getResponseLanguageMap()->{$this->ResponseLanguage};
    }
}