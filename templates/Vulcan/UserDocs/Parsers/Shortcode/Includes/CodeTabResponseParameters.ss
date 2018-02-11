<div class="userdocs-codetab parameters">
    <div class="userdocs-codetab-title"><span class="userdocs-codetab-method border-right-0 mr-0 pr-0"><%t VulcanUserDocs.CODETAB_RESPONSE_PARAMS_TITLE 'Response Parameters' %></span></div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-light">
            <tr>
                <th class="font-weight-light">Parameter</th>
                <th class="font-weight-light">Type</th>
                <th class="font-weight-light">Explanation</th>
            </tr>
            </thead>
            <tbody>
                <% loop $ResponseParameters %>
                <tr<% if $Children %> class="has-children"<% end_if %>>
                    <td>[code]$Parameter[/code]</td>
                    <td><% loop $Types %>[code]$Title[/code]<% if not $Last %>|<% end_if %><% end_loop %></td>
                    <td>$ParsedExplanation</td>
                </tr>
                    <% if $Children %>
                        <% loop $Children %>
                        <tr class="child-parameter">
                            <td>[code]$Parameter[/code]</td>
                            <td><% loop $Types %>[code]$Title[/code]<% if not $Last %>|<% end_if %><% end_loop %></td>
                            <td>$ParsedExplanation.RAW</td>
                        </tr>
                        <% end_loop %>
                    <% end_if %>
                <% end_loop %>
            </tbody>
        </table>
    </div>
</div>