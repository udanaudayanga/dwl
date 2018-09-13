 </div>
        </div>
        <footer class="app-footer">
            <div class="wrapper">
                <span class="pull-right"> <a href="#"><i class="fa fa-long-arrow-up"></i></a></span> © 2017 Copyright.
            </div>
        </footer>
    <div>

    <!-- Javascript Libs -->
            
            
            
            <script type="text/javascript" src="/assets/js/bootstrap-switch.min.js"></script>
            <script type="text/javascript" src="/assets/js/jquery.matchHeight-min.js"></script>
            <script type="text/javascript" src="/assets/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js"></script>
            <script type="text/javascript" src="/assets/js/select2.full.min.js"></script>
            <script type="text/javascript" src="/assets/js/ace.js"></script>
            <script type="text/javascript" src="/assets/js/mode-html.js"></script>
            <script type="text/javascript" src="/assets/js/theme-github.js"></script>
	    <script type="text/javascript" src="/assets/js/datepicker/js/bootstrap-datepicker.min.js"></script>
	    <script type="text/javascript" src="/assets/js/jqueryui/jquery-ui.js"></script>
            <script type="text/javascript" src="/assets/js/bootbox.min.js"></script>
            <!-- Javascript -->
            <script type="text/javascript" src="/assets/js/app.js"></script>
           
	    <!--<script type="text/javascript" src="/assets/js/modal.js"></script>-->
            
	    
	    <script type="text/javascript">
	    

    
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#pm" )
      // don't navigate away from the field on tab when selecting an item
      .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        source: function( request, response ) {
          $.getJSON( BASE_URL+'patient/getPrevMeds', {
            term: extractLast( request.term )
          }, response );
        },
        search: function() {
          // custom minLength
          var term = extractLast( this.value );
          if ( term.length < 2 ) {
            return false;
          }
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
	    </script>
</body>

</html>
