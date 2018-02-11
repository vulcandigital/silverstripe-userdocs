<?php

namespace Vulcan\UserDocs\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\View\Parsers\ShortcodeParser;

/**
 * Class CodeTabRequestParameter
 * @package Vulcan\UserDocs\Models
 *
 * @property string Parameter
 * @property string Explanation
 *
 * @property int    TabID
 *
 * @method CodeTab Tab
 */
class CodeTabRequestParameter extends DataObject
{
    private static $table_name = 'CodeTabRequestParameter';

    private static $singular_name = 'Request Parameter';

    private static $plural_name = 'Request Parameters';

    private static $db = [
        'Parameter'   => 'Varchar(50)',
        'Explanation' => 'HTMLText',
        'Sort'        => 'Int'
    ];

    private static $has_one = [
        'Tab' => CodeTab::class
    ];

    private static $summary_fields = [
        'Parameter'                  => 'Parameter',
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

        return $result;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        $this->Parameter = trim($this->Parameter);
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['Sort']);

        return $fields;
    }

    public function getTitle()
    {
        return $this->Parameter;
    }

    public function getParsedExplanation()
    {
        return ShortcodeParser::get_active()->parse($this->Explanation);
    }
}
