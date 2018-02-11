<div class="userdocs-codetab parameters">
    <div class="userdocs-codetab-title"><span class="userdocs-codetab-method border-right-0 mr-0 pr-0"><%t VulcanUserDocs.CODETAB_REQUEST_PARAMS_TITLE 'Request Parameters' %></span></div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-light">
            <tr>
                <th class="font-weight-light">Parameter</th>
                <th class="font-weight-light">Explanation</th>
            </tr>
            </thead>
            <tbody>
                <% loop $RequestParameters %>
                <tr>
                    <td>[code]$Parameter[/code]</td>
                    <td>$ParsedExplanation.RAW</td>
                </tr>
                <% end_loop %>
            </tbody>
        </table>
    </div>
</div>