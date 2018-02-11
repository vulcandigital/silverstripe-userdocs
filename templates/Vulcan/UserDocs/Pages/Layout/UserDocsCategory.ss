<div class="container">
    $UserDocsBreadcrumb
    <div class="row">
        <div class="col-md-4">
            <% include Vulcan\UserDocs\Pages\SideNav %>
        </div>
        <div class="col-md-8">
            <div class="userdocs-content">
                <% if $Content %>$Content<p>&nbsp;</p><% end_if %>
                $RenderContents.RAW
            </div>
        </div>
    </div>
</div>