<div class="modal fade modal-info"  id="chart_<?php echo $patient_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>			
                <h4 class="modal-title" id="myModalLabel">Weight History</h4>
            </div>
            <div class="modal-body">
                <table class="highchart"  data-graph-container=".. .. .highchart-container" data-graph-type="line" style="display: none;">
              <caption>Weight over visits</caption>
              <thead>
                <tr>
                  <th>Visits</th>
                  <th>Actual</th>
                  <th>Expected</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>04/28/2016</td>
                  <td>250</td>
                  <td>250</td>
                </tr>
                <tr>
                  <td>05/05/2016</td>
                  <td>230</td>
                  <td>240</td>
                </tr>
                <tr>
                  <td>05/12/2016</td>
                  <td>240</td>
                  <td>230</td>
                </tr>
                <tr>
                  <td>05/19/2016</td>
                  <td>225</td>
                  <td>220</td>
                </tr>
                <tr>
                  <td>05/26/2016</td>
                  <td>220</td>
                  <td>210</td>
                </tr>
                <tr>
                  <td>05/26/2016</td>
                  <td>210</td>
                  <td>200</td>
                </tr>
              </tbody>
            </table>
                <div class="highchart-container">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>