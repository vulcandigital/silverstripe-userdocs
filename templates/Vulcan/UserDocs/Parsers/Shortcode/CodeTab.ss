<div class="userdocs-codetab">
    <div class="userdocs-codetab-title"><% if $RequestMethod %><span class="userdocs-codetab-method">$RequestMethod</span><% end_if %>$Title</div>
    <ul class="nav nav-tabs" id="$Slug" role="tablist">
        <% loop $Examples %>
            <li class="nav-item">
                <a class="nav-link<% if $First %> active<% end_if %>" id="{$Up.Slug}-$Language-tab" data-toggle="tab" href="#{$Up.Slug}-$Language" role="tab" aria-controls="{$Up.Slug}-$Language" aria-selected="true">$ReadableLanguage</a>
            </li>
        <% end_loop %>
    </ul>
    <div class="tab-content" id="{$Slug}Content">
        <% loop $Examples %>
            <div class="tab-pane<% if $First %> show active<% end_if %>" id="{$Up.Slug}-$Language" role="tabpanel" aria-labelledby="{$Up.Slug}-$Language-tab">
                <pre><code class="$Language">$ParsedContent.RAW</code></pre>
            </div>
        <% end_loop %>
    </div>
</div>
<% if $RequestParameters %>
    <% include Vulcan\UserDocs\Parsers\Shortcode\Includes\CodeTabRequestParameters %>
<% end_if %>
<% if $ResponseParameters %>
    <% include Vulcan\UserDocs\Parsers\Shortcode\Includes\CodeTabResponseParameters %>
<% end_if %>
<% if $Response %>
    <% include Vulcan\UserDocs\Parsers\Shortcode\Includes\CodeTabResponse %>
<% end_if %>