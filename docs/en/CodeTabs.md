# Code Tabs
A "code tab" is a tabbed-panel with a collection of syntax-highlighted code examples and documented request/response parameters.

| Field | Description |
|---|---|
| Language | The language that this example represents (_you may only have one of any language per code tab_) |
| Request Method | Specify which HTTP method is required for this tab (optional - useful for documenting API endpoints) |
| Slug | A randomly generated slug, which is required to render this template using a [shortcode](Shortcodes.md) |
| Example Response | If this tab represents an API endpoint. It may be useful for the end-user to provide what that endpoint would respond with (optional - can contain your own shortcodes if used wisely however doing so _may_ have unexpected results) |
| Response Language | What language is the response returned in, allowed values are `JSON` and `XML` |

## Preview
![CodeTabs, done right](https://i.imgur.com/kNO7L6D.png)

## Examples
You can add as many example as you want with syntax-highlighting to boot.

| Field | Description |
|---|---|
| Language | The language of which this example is supplied in (used in syntax highlighting). For a complete list of languages click [here](#available-languages) |
| Content | The raw code in the language specified above |

## Request Parameters
Providing these parameters are only useful (and relevant) when documenting an API

| Field | Description |
|---|---|
| Parameter | The request parameter, eg. `foo=bar` would be `foo` |
| Explanation | An explanation of the request parameter |

## Response Parameters
Providing these parameters are only useful (and relevant) when documenting an API

| Field | Description |
|---|---|
| Parameter | The response parameter, eg. `foo=bar` would be `foo` |
| Types | One or more type representing the value from the response parameter, types available are: `string`, `integer`, `float`, `boolean`, `array` or `null` |
| Explanation | An explanation of the response parameter |
| Child Parameters (tab) | If you have specified `array` as a return type, this tab will appear allowing you to document the parameters within that array |

## Available Languages
The below languages are available for syntax highlighting and can be selected as the language of an example. _Always use the alias when dealing with languages in shortcodes_.

| Language | Alias |
|---|---|
|1C|1c|
|ABNF|abnf|
|Access logs|accesslog|
|Ada|ada|
|ARM assembler|armasm|
|AVR assembler|avrasm|
|ActionScript|actionscript|
|Apache|apache|
|AppleScript|applescript|
|AsciiDoc|asciidoc|
|AspectJ|aspectj|
|AutoHotkey|autohotkey|
|AutoIt|autoit|
|Awk|awk|
|Axapta|axapta|
|Bash|bash|
|Basic|basic|
|BNF|bnf|
|Brainfuck|brainfuck|
|C#|csharp|
|C++|cpp|
|C/AL|cal|
|Cache Object Script|cos|
|CMake|cmake|
|Coq|coq|
|CSP|csp|
|CSS|css|
|Cap’n Proto|capnproto|
|Clojure|clojure|
|CoffeeScript|coffeescript|
|Crmsh|crmsh|
|Crystal|crystal|
|D|d|
|DNS Zone file|dns|
|DOS|dos|
|Dart|dart|
|Delphi|delphi|
|Diff|diff|
|Django|django|
|Dockerfile|dockerfile|
|dsconfig|dsconfig|
|DTS (Device Tree)|dts|
|Dust|dust|
|EBNF|ebnf|
|Elixir|elixir|
|Elm|elm|
|Erlang|erlang|
|Excel|excel|
|F#|fsharp|
|FIX|fix|
|Fortran|fortran|
|G-Code|gcode|
|Gams|gams|
|GAUSS|gauss|
|Gherkin|gherkin|
|Go|go|
|Golo|golo|
|Gradle|gradle|
|Groovy|groovy|
|HTML|html|
|HTTP|http|
|Haml|haml|
|Handlebars|handlebars|
|Haskell|haskell|
|Haxe|haxe|
|Hy|hy|
|Ini|ini|
|Inform7|inform7|
|IRPF90|irpf90|
|JSON|json|
|Java|java|
|JavaScript|javascript|
|Leaf|leaf|
|Lasso|lasso|
|Less|less|
|LDIF|ldif|
|Lisp|lisp|
|LiveCode Server|livecodeserver|
|LiveScript|livescript|
|Lua|lua|
|Makefile|makefile|
|Markdown|markdown|
|Mathematica|mathematica|
|Matlab|matlab|
|Maxima|maxima|
|Maya Embedded Language|mel|
|Mercury|mercury|
|Mizar|mizar|
|Mojolicious|mojolicious|
|Monkey|monkey|
|Moonscript|moonscript|
|N1QL|n1ql|
|NSIS|nsis|
|Nginx|nginx|
|Nimrod|nimrod|
|Nix|nix|
|OCaml|ocaml|
|Objective C|objectivec|
|OpenGL Shading Language|glsl|
|OpenSCAD|openscad|
|Oracle Rules Language|ruleslanguage|
|Oxygene|oxygene|
|PF|pf|
|PHP|php|
|Parser3|parser3|
|Perl|perl|
|Pony|pony|
|PowerShell|powershell|
|Processing|processing|
|Prolog|prolog|
|Protocol Buffers|protobuf|
|Puppet|puppet|
|Python|python|
|Python profiler results|profile|
|Q|k|
|QML|qml|
|R|r|
|RenderMan RIB|rib|
|RenderMan RSL|rsl|
|Roboconf|graph|
|Ruby|ruby|
|Rust|rust|
|SCSS|scss|
|SQL|sql|
|STEP Part 21|p21|
|Scala|scala|
|Scheme|scheme|
|Scilab|scilab|
|Shell|shell|
|Smali|smali|
|Smalltalk|smalltalk|
|Stan|stan|
|Stata|stata|
|Stylus|stylus|
|SubUnit|subunit|
|Swift|swift|
|Test Anything Protocol|tap|
|Tcl|tcl|
|TeX|tex|
|Thrift|thrift|
|TP|tp|
|Twig|twig|
|TypeScript|typescript|
|VB.Net|vbnet|
|VBScript|vbscript|
|VHDL|vhdl|
|Vala|vala|
|Verilog|verilog|
|Vim Script|vim|
|x86 Assembly|x86asm|
|XL|xl|
|XQuery|xpath|
|Zephir|zephir|
