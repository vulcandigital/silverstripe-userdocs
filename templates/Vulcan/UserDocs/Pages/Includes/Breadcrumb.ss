<% if $Breadcrumbs %>
<nav aria-label="breadcrumb" class="userdocs-breadcrumb">
    <ol class="breadcrumb">
        <% loop $Breadcrumbs %>
            <% if not $Last %>
            <li class="breadcrumb-item"><a href="$Link">$Title</a></li>
            <% else %>
            <li class="breadcrumb-item active" aria-current="page">$Title</li>
            <% end_if %>
        <% end_loop %>
    </ol>
</nav>
<% end_if %>