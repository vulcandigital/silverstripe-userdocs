<div class="userdocs-nav">
    <h2><%t VulcanUserDocs.NAV_TITLE 'Navigation' %></h2>
    <ul class="nav flex-column">
        <% loop $Menu(2) %>
            <li class="nav-item<% if $Children %> has-children<% end_if %>">
                <a class="nav-link<% if $LinkingMode == 'current' %> active<% end_if %>" href="$Link">$Title<% if $Children %><i class="fa fa-caret-down pull-right"></i><% end_if %></a><% if $Children %><% include Vulcan\UserDocs\Pages\SideNavChildren %><% end_if %>
            </li>
        <% end_loop %>
    </ul>
</div>