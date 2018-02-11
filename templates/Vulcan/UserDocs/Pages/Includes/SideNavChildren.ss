<ul>
    <% loop $Children %>
        <li class="nav-item"><a class='nav-link<% if $LinkingMode == 'current' %> active<% end_if %>' href="$Link">$MenuTitle.XML</a><% if $Children %><% include Vulcan\UserDocs\Pages\SideNavChildren %><% end_if %></li>
    <% end_loop %>
</ul>