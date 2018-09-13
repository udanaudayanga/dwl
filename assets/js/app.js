$(function() {
  $(".navbar-expand-toggle").click(function() {
    $(".app-container").toggleClass("expanded");
    return $(".navbar-expand-toggle").toggleClass("fa-rotate-90");
  });
  return $(".navbar-right-expand-toggle").click(function() {
    $(".navbar-right").toggleClass("expanded");
    return $(".navbar-right-expand-toggle").toggleClass("fa-rotate-90");
  });
});

$(function() {
  return $('select').not('.not_select2').select2();
});

$(function() {
  return $('.toggle-checkbox').bootstrapSwitch({
    size: "small"
  });
});

$(function() {
  return $('.match-height').matchHeight();
});

$(function() {
  return $('.datatable').DataTable({
    "dom": '<"top"fl<"clear">>rt<"bottom"ip<"clear">>'
  });
});

$(function() {
  return $(".side-menu .nav .dropdown").on('show.bs.collapse', function() {
    return $(".side-menu .nav .dropdown .collapse").collapse('hide');
  });
});

$(function()
{

//    $('.check_history_btn').on('click',function(e){
//        e.preventDefault();
//        _target = $(this);
//        
//        var pp_id = _target.data('ppid');
//        _modal = $('#prepaid_history');
//        
//        $.get(BASE_URL+'patient/getPPHistory/'+pp_id,function(data){
//            _modal.find('.modal-body').html(data);
//            _modal.find('.datatable').dataTable();
//            
//        });
//        
//         _modal.modal();
//    });
    
    $('#cart_add_cat').on('change',function(e){
       $('.cart_error').addClass('hide');
        $.get(BASE_URL+'order/getCatProDropDown/'+this.value,function(data){            
            $('#cart_pro_dd_div').html(data);
        });
        
    });
    
    $('#add_pro_cart_btn').on('click',function(e){
        $('.cart_error').addClass('hide');
        $.post(BASE_URL+'order/addProCart',$('#add_pro_cart').serialize(),function(data){
            data = JSON.parse(data);
            if(data.status == 'success')
            {
                $('#order_cart_div').html(data.cart);
            }
            else if(data.status == 'error')
            {
                $('#js_cart_error strong').html(data.msg)
                $('#js_cart_error').removeClass('hide');
            }
            
        });
    });
    
    $('#order_cart_div').on('click','.del_cart_pro',function(e){
        e.preventDefault();
        $('.cart_error').addClass('hide');
        _row_id = $(this).data('rowid');
        _free_id = $(this).data('combineid');
        _pid = $(this).data('pid');
        if(confirm('Are you sure?'))
        {
            $.post(BASE_URL+'order/delPro',{raw_id:_row_id,combine_id:_free_id,pid:_pid},function(data){
                $('#order_cart_div').html(data);
            });
        }
        
        return false;
    });
    
    $('#patient_cat').on('change',function(e){
        _cat = $(this).val();
        $('#js_cart_error').addClass('hide');
        $.post(BASE_URL+'order/setPatientCat',{pcat:_cat},function(data){
                $('#order_cart_div').html(data);
        });        
    });
    

    
    $(".decimal_field").on('change keyup paste blur', function() {
        if($(this).val().indexOf('.')!=-1){         
            if($(this).val().split(".")[1].length > 1){                
                if( isNaN( parseFloat( this.value ) ) ) return;
                this.value = parseFloat(this.value).toFixed(1);
            }  
         }        
    });
    
    $('#dr_patient_visits').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
    
    $('#log_print').on('click',function(){
        _start = $('#start').val();
        _end = $('#end').val();
        _location = $('#location').val();
        
        if($.trim(_start) == '' || $.trim(_end) == '')
        {
            bootbox.alert('Date range is a required field.');
        }
        else
        {
            var win = window.open(BASE_URL+'logs/report/'+_location+'/'+_start+'/'+_end, '_blank');
            if(win){
                //Browser has allowed it to be opened
                win.focus();
            }
        }
    });
    
    var cache = {};
    $("#patient_refferral_name" ).autocomplete({
      minLength: 2,
      source: function( request, response ) {
        var term = request.term;
        if ( term in cache ) {
          response( cache[ term ] );
          return;
        }
 
        $.getJSON( BASE_URL+'patient/getActivePatients', request, function( data, status, xhr ) {
          cache[ term ] = data;
          response( data );
        });
      },
      select: function( event, ui ) {
	  $('#patient_refferral_id').val(ui.item.id);
	  $('#new_patient').prop('readonly', false);
	    $('#old_patient').prop('readonly', false);
      }
    });
});

    function validateQty(event) {
        var key = window.event ? event.keyCode : event.which;
        if (event.keyCode == 8 || event.keyCode == 46
         || event.keyCode == 37 || event.keyCode == 39) {
            return true;
        }
        else if ( key < 48 || key > 57 ) {
            return false;
        }
        else return true;
    };
    
    function GetURLParameter(param) {
            var vars = {};
            window.location.href.replace( location.hash, '' ).replace( 
                    /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
                    function( m, key, value ) { // callback
                            vars[key] = value !== undefined ? value : '';
                    }
            );

            if ( param ) {
                    return vars[param] ? vars[param] : null;	
            }
            return vars;
        }

