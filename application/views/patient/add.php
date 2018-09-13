<?php $this->load->view('template/header');?>

<script type="text/javascript" src="/assets/js/jquery.maskedinput.min.js"></script>
    
	<form method="POST" enctype="multipart/form-data">
	<div class="row">
	    
	    
		<div class="card">
		    <div class="card-header">
			<div class="card-title">
			    <div class="title">Add Patient</div>
			</div>
		    </div>
                    <div class="card-body" style="padding: 25px 0px;">	
                            <div class="col-xs-12">
                                <?php echo $errors; ?>
                            </div>
			    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12" style="">
				<label for="lname">Last Name</label>
				<input type="text" required="required" name="lname" placeholder="Last Name" id="lname" value="<?php echo set_value("lname");?>" class="form-control">
			    </div>
			    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12" style="">				
				<label for="fname">First Name </label>
				<input type="text" required="required" name="fname" placeholder="First Name" value="<?php echo set_value("fname");?>" id="fname" class="form-control">
			    </div>
                            <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12" style="height: 62px;">
				<label for="exampleInputEmail1">Gender</label>
                                <select style="width: 100%;" required="required" name="gender" class="form-control">
                                    <option <?php echo set_select('gender',2);?> value="2">Female</option>
				    <option <?php echo set_select('gender',1);?> value="1">Male</option>				    
				</select>
			    </div>

                            <div class="form-group col-xs-12">
				<label for="">Address</label>
				<div class="panel panel-default"  style="margin-bottom: 0px;">
                                    <div class="panel-body" style="padding: 5px;">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 5px 15px;">
					    <input type="text" value="<?php echo set_value("address");?>" name="address" placeholder="Address" id="autocomplete" class="form-control">
					</div>
					<div class="col-lg-2 col-md-2 col-sm-4 col-xs-4" style="padding: 5px 15px;">
					    <input type="text" value="<?php echo set_value("city");?>" name="city" placeholder="City" id="city" class="form-control">
					</div>					
					<div class="col-lg-2 col-md-2 col-sm-4 col-xs-4" style="padding: 5px 15px;">
                                            <select style="width: 100%;" required="required" name="state" id="state" class="form-control">
                                                <?php foreach($states as $state){?>
                                                    <option <?php echo set_select('state',$state->id,$state->id==10);?> data-abbr="<?php echo $state->abbr;?>" value="<?php echo $state->id;?>"><?php echo $state->name;?></option>
                                                <?php } ?>
                                            </select>
					    <!--<input type="text" value="<?php echo set_value("state");?>" name="state" placeholder="State" id="" class="form-control">-->
					</div>
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4" style="padding: 5px 15px;">
					    <input type="text" value="<?php echo set_value("zip");?>" name="zip" placeholder="Zip Code" id="zip" class="form-control">
					</div>
				    </div>
				</div>
			    </div>
                            <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12" style="">
				<label for="cell_phone">Cell Phone #</label>
				<input name="phone" type="text" placeholder="(999) 999-9999" value="<?php echo set_value("phone");?>" id="phone" class="form-control">
			    </div>
                            <div class="form-group col-lg-1_5 col-md-1_5 col-sm-4 col-xs-8" style="">
				<label for="dob">Date of birth</label>
				<input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="mm/dd/yyyy" placeholder="MM/DD/YYYY" name="dob" value="<?php echo set_value("dob");?>" id="dob" class="form-control">
			    </div>
                             <div class="form-group col-lg-1_5 col-md-1_5 col-sm-2 col-xs-4" style="padding-left: 0px;">
				<label for="dob_text">&nbsp;</label>
				<input type="text" id="dob_text" value="" readonly="readonly" class="form-control">
			    </div>
                             <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12" style="">
                                <label for="goal_weight">Goal Weight</label>
				<input  type="text" required="required" name="goal_weight" placeholder="Eg: 204.5" id="goal_weight" value="<?php echo set_value("goal_weight");?>"  pattern="^[0-9]*(\.[0-9]{1}?)?$" class="form-control decimal_field">
			    </div>
                            <div class="form-group col-lg-1_5 col-md-1_5 col-sm-4 col-xs-8" style="">
				<label for="height">Height</label>
				<input name="height" type="number" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Inches" value="<?php echo set_value("height");?>" id="height" class="form-control">
			    </div>
			    <div class="form-group col-lg-1_5 col-md-1_5 col-sm-2 col-xs-4" style="padding-left: 0px;">
				<label for="height_in_feet">&nbsp;</label>
				<input type="text" id="height_in_feet" value="" readonly="readonly" class="form-control">
			    </div>
                           
                            <div class="form-group col-lg-3 col-md-3 col-sm-4 col-xs-12" style="">
				<label for="email">Email</label>
				<input type="email"  placeholder="Email" id="email" value="<?php echo set_value("email");?>" name="email" class="form-control">
			    </div>
                            
			    <div class="form-group col-lg-3 col-md-3 col-sm-4 col-xs-12" style="height: 62px;">
				<label for="photo">Photo</label>
				<input accept="image/gif, image/jpeg, image/jpg, image/png" type="file" id="photo" name="photo">
			    </div>
                            <?php if(in_array($user->type,array(1,4))){?>
                            <div class="form-group col-lg-3 col-md-3 col-sm-4 col-xs-12" style="height: 62px;">
				<label for="exampleInputEmail1">Patient Type</label>
                                <select style="width: 100%;" required="required" name="patient_category" class="form-control">
				    <option <?php echo set_select('patient_category','regular');?> value="regular">Regular</option>
				    <option <?php echo set_select('patient_category','staff');?> value="staff">Staff</option>
                                    <option <?php echo set_select('patient_category','family');?> value="family">Family</option>
				</select>
			    </div>
                            <?php } ?>
                            
			    <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-top: 10px;">
				<label for="prn" style="font-family: 'Ranga', cursive;">New Patient referral (Injection Split)</label>
				<input type="text" placeholder="Find Old Patient" id="patient_refferral_name" name="patient_refferral_name" value="<?php echo set_value("patient_refferral_name");?>" class="form-control">
				<input type="hidden" name="patient_refferral_id" id="patient_refferral_id" value="<?php echo set_value("patient_refferral_id");?>"/>
			    </div>
			    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xs-6" style="margin-top: 10px;">
				<label for="new_patient" style="font-family: 'Ranga', cursive;">New Patient Split</label>
				<input type="number" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="New patinent share" name="new_patient"  value="<?php echo set_value("new_patient");?>" id="new_patient" class="form-control">
			    </div>
			    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xs-6" style="margin-top: 10px;">
				<label for="old_patient" style="font-family: 'Ranga', cursive;">Old Patient Split</label>
				<input type="number" min="0" step="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Old patinent share" name="old_patient" value="<?php echo set_value("old_patient");?>" id="old_patient" class="form-control">
			    </div>
			    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top: 10px;">
				<label for="pm">Current medication</label>
				<textarea placeholder="NONE" rows="1" name="previous_medication" id="pm" class="form-control red_placeholder"><?php echo set_value('previous_medication');?></textarea>
			    </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top: 10px;">
				<label for="allergies">Allergies</label>
				<textarea placeholder="NKA" rows="1" name="allergies" id="allergies" class="form-control red_placeholder"><?php echo set_value('allergies');?></textarea>
			    </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-6" style="margin-top: 10px;">
				<label for="staff">Staff</label>
                                <select class="form-control" name="staff" id="staff">
                                    <option value="">Select Staff</option>
                                    <?php foreach($staff as $stf){?>
                                    <option value="<?php echo $stf->id;?>"><?php echo $stf->lname." ".$stf->fname;?></option>
                                    <?php } ?>
                                </select>
			    </div>
                            <div class="form-group col-md-8 col-sm-6 col-xs-6" style="text-align: right;margin-top: 22px;">
				<button class="btn btn-success" type="submit">Submit</button>
			    </div>
		    </div>
		</div>
	</div>
	</form>
<script type="text/javascript">
    $(function(){
	if($('#patient_refferral_id').val())
	{
	    $('#new_patient').prop('readonly', false);
	    $('#old_patient').prop('readonly', false);
	}
	else
	{
	    $('#new_patient').prop('readonly', true);
	    $('#old_patient').prop('readonly', true);
	}
	
	if($('#height').val())
	{
	    $('#height_in_feet').val(inFeet($('#height').val()));
	}
	
	if($('#dob').val())
	{
	    $('#dob_text').val(getAge(new Date($("#dob").val())));
	}
	
	$("#height").on('change keyup paste', function() {
	    $('#height_in_feet').val(inFeet($('#height').val()));
	});
	
	$("#dob").on('change keyup paste', function() {
	    $('#dob_text').val(getAge(new Date($("#dob").val())));
	});
	
	function inFeet(inches)
	{
	    var feet = Math.floor(inches / 12);
	    inches %= 12;
	    
	    return feet + ' feet ' + inches + ' inch';
	}
	
	function getAge(birthday) { // birthday is a date
	    var ageDifMs = Date.now() - birthday.getTime();
	    var ageDate = new Date(ageDifMs); // miliseconds from epoch
	    return Math.abs(ageDate.getUTCFullYear() - 1970)+ ' Years';
	}
        
        
        $("#phone").mask("(999) 999-9999");
        
        $('#lname').on('blur',function(){
            _lname = $(this).val();
            if(_lname.length)
            {
                $.post(BASE_URL+'patient/getByFname',{lname:_lname},function(data){
                    data = JSON.parse(data);
                    
                    if(data.status == 'exist')
                    {
                        bootbox.alert(data.msg);
                    }
                });
            }
        });
    });
    
    
      var placeSearch, autocomplete;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
      };

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode'],componentRestrictions: {country: "us"}});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        $('#zip').val('');
        $('#city').val('');
        $('#autocomplete').val('');
//        for (var component in componentForm) {
//          document.getElementById(component).value = '';
//          document.getElementById(component).disabled = false;
//        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          console.log(addressType+' : '+ place.address_components[i][componentForm[addressType]]);
          var val = place.address_components[i][componentForm[addressType]];
          if(addressType == 'postal_code') $('#zip').val(val);
          if(addressType == 'locality') $('#city').val(val);
          if(addressType == 'administrative_area_level_1')
              $('#state option[data-abbr="' + val + '"]').prop('selected', true).change();
          
          if(addressType == 'street_number') $('#autocomplete').val(val);
          if(addressType == 'route') $('#autocomplete').val($('#autocomplete').val()+" "+val);
          
//          if (componentForm[addressType]) {
//            var val = place.address_components[i][componentForm[addressType]];
//            document.getElementById(addressType).value = val;
//          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDcksmYq9LNc4qlat28oirpHK6SWtSNEf4&libraries=places&callback=initAutocomplete"
        async defer></script>
<?php $this->load->view('template/footer');?>