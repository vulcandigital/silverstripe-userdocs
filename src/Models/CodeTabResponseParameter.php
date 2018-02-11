<?php

namespace Vulcan\UserDocs\Models;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\ListboxField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\HasManyList;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\View\Parsers\ShortcodeParser;

/**
 * Class CodeTabResponseParameter
 * @package Vulcan\UserDocs\Models
 *
 * @property string Parameter
 * @property string Explanation
 *
 * @property int    TabID
 * @property int    ParentID
 *
 * @method CodeTab Tab
 * @method CodeTabResponseParameter Parent
 *
 * @method HasManyList|CodeTabResponseParameter[] Children
 * @method ManyManyList|CodeTabParameterType[] Types
 */
class CodeTabResponseParameter extends DataObject
{
    private static $table_name = 'CodeTabResponseParameter';

    private static $singular_name = 'Response Parameter';

    private static $plural_name = 'Response Parameters';

    private static $db = [
        'Parameter'   => 'Varchar(50)',
        'Explanation' => 'HTMLText',
        'Sort'        => 'Int'
    ];

    private static $has_one = [
        'Tab'    => CodeTab::class,
        'Parent' => self::class
    ];

    private static $has_many = [
        'Children' => self::class
    ];

    private static $many_many = [
        'Types' => CodeTabParameterType::class
    ];

    private static $summary_fields = [
        'Parameter'                  => 'Parameter',
        'TypesAsString'              => 'Value Types',
        'Explanation.FirstParagraph' => 'Explanation'
    ];

    public function validate()
    {
        $result = parent::validate();

        if (!$this->Parameter) {
            $result->addFieldError('Parameter', 'You must provide the name of this parameter');
        }

        if (!$this->Explanation) {
            $result->addFieldError('Explanation', 'You must describe the purpose of this parameter');
        }

        if (!$this->Types()->exists()) {
            $result->addFieldError('Types', 'You must select at least one value type');
        }

        return $result;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['ParentID', 'TabID', 'Children', 'Types', 'Sort']);
        $fields->dataFieldByName('Explanation')->setRows(5);
        $fields->addFieldsToTab('Root.Main', [
            ListboxField::create('Types', 'Types', CodeTabParameterType::get(), $this->Types())
        ], 'Explanation');

        if ($this->ID && $this->Types()->find('Title', 'array')) {
            $fields->addFieldsToTab('Root.ChildParameters', [
                GridField::create('Children', 'Children', $this->Children(), GridFieldConfig_RecordEditor::create())
            ]);
        }

        return $fields;
    }

    public function getTitle()
    {
        return sprintf('%s : %s', $this->Parameter, $this->getTypesAsString());
    }

    public function getTypesAsString()
    {
        return implode('|', $this->Types()->column('Title'));
    }

    public function getParsedExplanation()
    {
        return ShortcodeParser::get_active()->parse($this->Explanation);
    }
}