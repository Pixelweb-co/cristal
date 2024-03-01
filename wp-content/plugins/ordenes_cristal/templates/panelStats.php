            <div class="statsPanel">
            
            <ul class="list-group mt-3 list-stats">
                <li class="list-group-item" ng-repeat="st in stats">
                    <span class="text-item-title-cat"> <b>{{st.title}}:</b> {{st.total_cat  | currency: '$'}}</span>
                    <div class="chart1">
                    <div class="progress">
                      <div class="progress-bar bg-stats-vi" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                      <div class="progress-bar bg-stats-da" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    </div>
                    <div class="price2 text-item-general" style="float: right;">$37.000</div>
                </li>

            </ul>

            </div>
         

<script type="text/javascript">

jQuery(document).ready(function($) {

})
</script>

