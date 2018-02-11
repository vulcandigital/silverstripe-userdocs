<% with $Parent %>
    <h2><%t VulcanUserDocs.CONTENTS_TITLE 'Contents' %></h2>
    <ul class="userdocs-contents">
        <% if $Children %>
            <% loop $Children %>
                <li><a href="$Link">$MenuTitle.XML</a><% if $Children %>
                    <ul>
                        <% loop $Children %>
                            <li><a href="$Link">$MenuTitle.XML</a><% if $Children %>
                                <ul>
                                    <% loop $Children %>
                                        <li><a href="$Link">$MenuTitle.XML</a><% if $Children %>
                                            <ul>
                                                <% loop $Children %>
                                                    <li><a href="$Link">$MenuTitle.XML</a>
                                                        <% if $Children %>
                                                            <ul>
                                                                <% loop $Children %>
                                                                    <li><a href="$Link">$MenuTitle.XML</a></li>
                                                                <% end_loop %>
                                                            </ul>
                                                        <% end_if %></li>
                                                <% end_loop %>
                                            </ul>
                                        <% end_if %></li>
                                    <% end_loop %>
                                </ul>
                            <% end_if %></li>
                        <% end_loop %>
                    </ul>
                <% end_if %></li>
            <% end_loop %>
        <% end_if %>
    </ul>
<% end_with %>