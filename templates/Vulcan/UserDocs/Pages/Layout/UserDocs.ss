<div class="container">
    $UserDocsBreadcrumb
    <div class="row">
        <div class="col-md-4 col-lg-3">
            <% include Vulcan\UserDocs\Pages\SideNav %>
        </div>
        <div class="col-md-8 col-lg-9">
            <div class="userdocs-content">
                $AnchoredContent.RAW
                <p>&nbsp;</p>
                $RenderContents
            </div>
        </div>
    </div>
</div>