# Shortcodes
These shortcodes are accessible through the entire site, not just pages belonging this module, however you can enable the syntax highlighter with the following JavaScript:

```javascript
hljs.configure({useBR: false});
hljs.initHighlightingOnLoad();
```
or you can just follow the instructions [here](GettingStarted.md#require-the-resources).

| Tag| Description |
|---|---|
|`[logged_in]` | This content will only be displayed if the user is logged in |
|`[not_logged_in]` | This content will only be displayed if the user is NOT logged in |
|`[code]` | Displays code inline much like this one here |
|`[code,display=block,lang=php]` | Display a highlighted block of code with a syntax of php. See [available languages](CodeTabs.md#available-languages) |
|`[codetab]` | Generates a predefined code tab, for more information see [CodeTabs](CodeTabs.md).  Must include the _slug ID_ for the code tab, for example `[codetab,id='your-unique-codetab-slug']` |