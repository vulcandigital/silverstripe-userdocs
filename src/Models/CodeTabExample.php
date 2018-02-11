<?php

namespace Vulcan\UserDocs\Models;

use SilverStripe\Forms\DropdownField;
use SilverStripe\i18n\i18n;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Parsers\ShortcodeParser;

/**
 * Class CodeTabExample
 * @package Vulcan\UserDocs\Models
 *
 * @property string Language
 * @property string Content
 *
 * @property int    TabID
 *
 * @method CodeTab Tab
 */
class CodeTabExample extends DataObject
{
    private static $table_name = 'CodeTabExample';

    private static $singular_name = 'Code Example';

    private static $plural_name = 'Code Example';

    private static $db = [
        'Language' => 'Varchar(20)',
        'Content'  => 'Text'
    ];

    private static $has_one = [
        'Tab' => CodeTab::class
    ];

    private static $summary_fields = [
        'ReadableLanguage' => 'Language',
        'LastEdited.Ago'   => 'Last Edited',
    ];

    public function validate()
    {
        $result = parent::validate();

        if (!$this->Language) {
            $result->addFieldError('Language', 'You must specify a language');
        }

        if (!$this->ID && static::get()->filter('Language', $this->Language)->first()) {
            $error = 'An example in that language already exists, please select another';
            $result->addFieldError('Language', $error);
        }

        if (!$this->Content) {
            $result->addFieldError('Content', 'You must provide content for this example');
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getReadableLanguage() . ' ' . i18n::_t('VulcanUserDocs.EXAMPLE_TITLE', 'Example');
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $languageField = DropdownField::create('Language', 'Language', static::getLanguageMap()->toMap())->setHasEmptyDefault(true)->setEmptyString('Please select ...');

        $fields->removeByName('RequestMethod');
        $fields->replaceField('Language', $languageField);

        return $fields;
    }

    public static function getLanguageMap()
    {
        return ArrayData::create([
            '1c'             => '1C',
            'abnf'           => 'ABNF',
            'accesslog'      => 'Access logs',
            'ada'            => 'Ada',
            'armasm'         => 'ARM assembler',
            'avrasm'         => 'AVR assembler',
            'actionscript'   => 'ActionScript',
            'apache'         => 'Apache',
            'applescript'    => 'AppleScript',
            'asciidoc'       => 'AsciiDoc',
            'aspectj'        => 'AspectJ',
            'autohotkey'     => 'AutoHotkey',
            'autoit'         => 'AutoIt',
            'awk'            => 'Awk',
            'axapta'         => 'Axapta',
            'bash'           => 'Bash',
            'basic'          => 'Basic',
            'bnf'            => 'BNF',
            'brainfuck'      => 'Brainfuck',
            'csharp'         => 'C#',
            'cpp'            => 'C++',
            'cal'            => 'C/AL',
            'cos'            => 'Cache Object Script',
            'cmake'          => 'CMake',
            'coq'            => 'Coq',
            'csp'            => 'CSP',
            'css'            => 'CSS',
            'capnproto'      => 'Cap’n Proto',
            'clojure'        => 'Clojure',
            'coffeescript'   => 'CoffeeScript',
            'crmsh'          => 'Crmsh',
            'crystal'        => 'Crystal',
            'd'              => 'D',
            'dns'            => 'DNS Zone file',
            'dos'            => 'DOS',
            'dart'           => 'Dart',
            'delphi'         => 'Delphi',
            'diff'           => 'Diff',
            'django'         => 'Django',
            'dockerfile'     => 'Dockerfile',
            'dsconfig'       => 'dsconfig',
            'dts'            => 'DTS (Device Tree)',
            'dust'           => 'Dust',
            'ebnf'           => 'EBNF',
            'elixir'         => 'Elixir',
            'elm'            => 'Elm',
            'erlang'         => 'Erlang',
            'excel'          => 'Excel',
            'fsharp'         => 'F#',
            'fix'            => 'FIX',
            'fortran'        => 'Fortran',
            'gcode'          => 'G-Code',
            'gams'           => 'Gams',
            'gauss'          => 'GAUSS',
            'gherkin'        => 'Gherkin',
            'go'             => 'Go',
            'golo'           => 'Golo',
            'gradle'         => 'Gradle',
            'groovy'         => 'Groovy',
            'html'           => 'HTML',
            'http'           => 'HTTP',
            'haml'           => 'Haml',
            'handlebars'     => 'Handlebars',
            'haskell'        => 'Haskell',
            'haxe'           => 'Haxe',
            'hy'             => 'Hy',
            'ini'            => 'Ini',
            'inform7'        => 'Inform7',
            'irpf90'         => 'IRPF90',
            'json'           => 'JSON',
            'java'           => 'Java',
            'javascript'     => 'JavaScript',
            'leaf'           => 'Leaf',
            'lasso'          => 'Lasso',
            'less'           => 'Less',
            'ldif'           => 'LDIF',
            'lisp'           => 'Lisp',
            'livecodeserver' => 'LiveCode Server',
            'livescript'     => 'LiveScript',
            'lua'            => 'Lua',
            'makefile'       => 'Makefile',
            'markdown'       => 'Markdown',
            'mathematica'    => 'Mathematica',
            'matlab'         => 'Matlab',
            'maxima'         => 'Maxima',
            'mel'            => 'Maya Embedded Language',
            'mercury'        => 'Mercury',
            'mizar'          => 'Mizar',
            'mojolicious'    => 'Mojolicious',
            'monkey'         => 'Monkey',
            'moonscript'     => 'Moonscript',
            'n1ql'           => 'N1QL',
            'nsis'           => 'NSIS',
            'nginx'          => 'Nginx',
            'nimrod'         => 'Nimrod',
            'nix'            => 'Nix',
            'ocaml'          => 'OCaml',
            'objectivec'     => 'Objective C',
            'glsl'           => 'OpenGL Shading Language',
            'openscad'       => 'OpenSCAD',
            'ruleslanguage'  => 'Oracle Rules Language',
            'oxygene'        => 'Oxygene',
            'pf'             => 'PF',
            'php'            => 'PHP',
            'parser3'        => 'Parser3',
            'perl'           => 'Perl',
            'pony'           => 'Pony',
            'powershell'     => 'PowerShell',
            'processing'     => 'Processing',
            'prolog'         => 'Prolog',
            'protobuf'       => 'Protocol Buffers',
            'puppet'         => 'Puppet',
            'python'         => 'Python',
            'profile'        => 'Python profiler results',
            'k'              => 'Q',
            'qml'            => 'QML',
            'r'              => 'R',
            'rib'            => 'RenderMan RIB',
            'rsl'            => 'RenderMan RSL',
            'graph'          => 'Roboconf',
            'ruby'           => 'Ruby',
            'rust'           => 'Rust',
            'scss'           => 'SCSS',
            'sql'            => 'SQL',
            'p21'            => 'STEP Part 21',
            'scala'          => 'Scala',
            'scheme'         => 'Scheme',
            'scilab'         => 'Scilab',
            'shell'          => 'Shell',
            'smali'          => 'Smali',
            'smalltalk'      => 'Smalltalk',
            'stan'           => 'Stan',
            'stata'          => 'Stata',
            'stylus'         => 'Stylus',
            'subunit'        => 'SubUnit',
            'swift'          => 'Swift',
            'tap'            => 'Test Anything Protocol',
            'tcl'            => 'Tcl',
            'tex'            => 'TeX',
            'thrift'         => 'Thrift',
            'tp'             => 'TP',
            'twig'           => 'Twig',
            'typescript'     => 'TypeScript',
            'vbnet'          => 'VB.Net',
            'vbscript'       => 'VBScript',
            'vhdl'           => 'VHDL',
            'vala'           => 'Vala',
            'verilog'        => 'Verilog',
            'vim'            => 'Vim Script',
            'x86asm'         => 'x86 Assembly',
            'xl'             => 'XL',
            'xpath'          => 'XQuery',
            'zephir'         => 'Zephir',
        ]);
    }

    public function getReadableLanguage()
    {
        return $this->getLanguageMap()->{$this->Language};
    }

    public function ParsedContent()
    {
        $content = DBHTMLText::create()->setValue(ShortcodeParser::get_active()->parse($this->Content));

        return $content;
    }
}
