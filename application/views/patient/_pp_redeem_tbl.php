<table class="table table-bordered">
      <thead>
          <tr>                                                        
              <th rowspan="2" style="width: 25%;text-align: center;vertical-align: middle;">Name</th>
              <th style="text-align: center;" colspan="3">Status</th>
              <th rowspan="2" style="width: 25%;text-align: center;vertical-align: middle;">Actions</th>
          </tr>
          <tr>
              <th style="text-align: center;">Remaining</th>
              <th style="text-align: center;">Today (Days / Qty)</th>
              <th style="text-align: center;">After Today</th>
          </tr>
      </thead>
      <tbody>
          <?php 
          $prepaids = getPrePaids($patient_id);
          foreach($prepaids as $pp){?>
              <tr>
                  <td><?php echo $pp->name;?></td>
                  <td style="text-align: center;"><input type="text" id="pp_remaining_<?php echo $pp->id;?>" readonly="readonly" class="form-control input-sm" style="width: 100%;text-align: center;" value="<?php echo $pp->remaining;?>"/></td>
                  <td style="text-align: center;">
                      <?php if($pp->cat_id == 4){?>
                      <select onselect="return validateQty(event);" style="width: 100%;" class="form-control not_select2 ppt" data-ppid="<?php echo $pp->id;?>" id="pp_today_<?php echo $pp->id;?>"  data-proid="<?php echo $pp->pro_id;?>">
                          <option value="0">SELECT</option>
                          <option value="7">7</option>
                          <option value="14">14</option>
                          <option value="21">21</option>
                          <option value="28">28</option>
                      </select>
                      <?php }else{?>
                      <input onkeypress='return validateQty(event);' type="number" id="pp_today_<?php echo $pp->id;?>" data-ppid="<?php echo $pp->id;?>" data-proid="<?php echo $pp->pro_id;?>" class="form-control input-sm ppt" style="width: 100%;text-align: center;" />
                      <?php } ?>
                  </td>
                  <td style="text-align: center;"><input type="text" id="pp_at_<?php echo $pp->id;?>" readonly="readonly" class="form-control input-sm" style="width: 100%;text-align: center;" /></td>
                  <td>
                      <a class="hover check_history_btn_new" data-ppid="<?php echo $pp->id;?>" data-proid="<?php echo $pp->pro_id;?>" style="font-weight: 500;color: #31708f;" href="">HISTORY</a>&nbsp;&nbsp;
                      <a data-patient="<?php echo $patient_id;?>" data-ppid="<?php echo $pp->id;?>" data-proid="<?php echo $pp->pro_id;?>" class="hover pp_update_new" style="font-weight: 500;color: #31708f;" href="">UPDATE</a>
                  </td>
              </tr>
          <?php } ?>
      </tbody>
  </table>