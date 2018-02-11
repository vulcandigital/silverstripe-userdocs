<?php

namespace Vulcan\UserDocs\Models;

use SilverStripe\ORM\DataObject;

/**
 * Class CodeTabParameterType
 * @package Vulcan\UserDocs\Models
 *
 * @property string Title
 * @property string ShortTitle
 */
class CodeTabParameterType extends DataObject
{
    private static $table_name = 'CodeTabParameterType';

    private static $db = [
        'Title'      => 'Varchar(20)',
        'ShortTitle' => 'Varchar(15)'
    ];

    private static $summary_fields = [
        'Title' => 'Type'
    ];

    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();

        foreach ($this->getTypeMap() as $shortTitle => $title) {
            $record = static::get()->filter(['Title' => $title, 'ShortTitle' => $shortTitle])->first();

            if ($record) {
                continue;
            }

            $record = static::create();
            $record->Title = $title;
            $record->ShortTitle = $shortTitle;
            $record->write();
        }
    }

    public function getTypeMap()
    {
        return [
            'string' => 'string',
            'int'    => 'integer',
            'bool'   => 'boolean',
            'float'  => 'float',
            'array' => 'array',
            'null'   => 'null'
        ];
    }
}