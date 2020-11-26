<div class="container">
    <div class="row">
    <div class="parent-list-container">
      <h2 class="list-bigtitle">All categories</h2>
        <div class="parent-list">
            <div class="cs-total-links-container" ng-repeat="subcategory in categories">
                <div class="cs-total-links">
                    <h3>@{{subcategory.title}}</h3>
                    <div class="for-scroll" custom-scroll="{ 'autoHide': false }" >
                    <a ng-repeat="section in subcategory.child_category" category-height ui-sref="categorysection({category:subcategory.parent_category.slug,slug:section.slug})"><span class="list-shot-spt">@{{section.title}}</span> <span class="link-list-count">( @{{section.videos_count[0].count}} )</span>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
