$(document).ready(function(){
		    
    // Use the .autocomplete() method to compile the list based on input from user
    $('#prococode1').autocomplete({
        source: 'item-data.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#prococode1').val(ui.item.rm_code);
                    //$itemrow.find('#procodesc').val(ui.item.desc);
                    //$itemrow.find('#procouom1').val(ui.item.uom);

                    getUprice(1);
                    // Give focus to the next input field to recieve input from user
                    $('#procosoldqty1').focus();

            return false;
	    }
    // Format the list menu output of the autocomplete
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.rm_code + " - " + item.desc + "</a>" )
            .appendTo( ul );
    };


    // Get the table object to use for adding a row at the end of the table
    var $itemsTable = $('#itemsTable');

   
    // Add row to list and allow user to use autocomplete to find items.
    $("#addRow").bind('click',function(){
    	var table = document.getElementById('itemsTable');
	    var rowCount = table.rows.length; 

    	 // Create an Array to for the table row. ** Just to make things a bit easier to read.
    var idprococode = "prococode"+rowCount;
    var idprocoupri = "procoupri"+rowCount;
    var idprocotype = "procotype"+rowCount;
    var idopening = "opening"+rowCount; 
    var idprocodoqty = "procodoqty"+rowCount;        
    var idprocosoldqty = "procosoldqty"+rowCount;
    var idprocosamt = "procosamt"+rowCount;
    var idprocortnqty = "procortnqty"+rowCount;        
    var idprocoshortqty = "procoshortqty"+rowCount;        
    var idprocooverqty = "procooverqty"+rowCount;        
    var idprocoadjqty = "procoadjqty"+rowCount;        
    var idprocobalqty = "procobalqty"+rowCount;        
    var idbegbal = "begbal"+rowCount;        
       
    var rowTemp = [
         '<tr class="item-row">',
            '<td><input name="seqno[]" class="tInput" id="seqno" readonly="readonly" style="width: 27px; border:0;"></td>',
            '<td><input name="prococode[]" value="" tProItem'+rowCount+'="1" id="'+idprococode+'" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)"></td>',
            '<td><input name="procoupri[]" value="" class="tInput" id="'+idprocoupri+'" style="width: 60px" ></td>',
            '<td><input name="procotype[]" value="" class="tInput" id="'+idprocotype+'" style=" width: 48px" onchange ="upperCase(this.id)"></td>',
            '<td><input name="opening[]" value="" class="tInput" id="'+idopening+'" style="width: 48px; text-align : right"></td>',
            '<td><input name="procodoqty[]" value="" class="tInput" id="'+idprocodoqty+'" style="width: 48px; text-align : right" ></td>',
            '<td><input name="procosoldqty[]" value="" class="tInput" id="'+idprocosoldqty+'" style="width: 48px; text-align : right" onBlur="getamt('+rowCount+');" >',
            '<input type="hidden" value="" id="'+idbegbal+'" ></td>',
            '<td><input name="procosamt[]" value="" class="tInput" id="'+idprocosamt+'" style="border-style: none; border-color: inherit; border-width: 0; width: 48px; text-align : right" readonly="readonly"></td>',
            '<td><input name="procortnqty[]" value="" class="tInput" id="'+idprocortnqty+'" style="width: 48px; text-align : right"></td>',
            '<td><input name="procoshortqty[]" value="" class="tInput" id="'+idprocoshortqty+'" style="width: 48px; text-align : right"></td>',
            '<td><input name="procooverqty[]" value="" class="tInput" id="'+idprocooverqty+'" style="width: 48px; text-align : right"></td>',
            '<td><input name="procoadjqty[]" value="" class="tInput" id="'+idprocoadjqty+'" style="width: 48px; text-align : right"  onBlur="getbal('+rowCount+');"></td>',
            '<td><input name="procobalqty[]" value="" class="tInput" id="'+idprocobalqty+'" style="border-style: none;width: 48px; text-align : right" readonly="readonly"></td>',
        '</tr>'
    ].join('');
	
    
        var $row = $(rowTemp);

        // save reference to inputs within row
        var $seqno  	        = $row.find('#seqno');
        var $prococode 	        = $row.find('#prococode'+rowCount);
        var $procotype	        = $row.find('#procotype'+rowCount);             
        var $procoupri          = $row.find('#procoupri'+rowCount);
        var $procodoqty         = $row.find('#procodoqty'+rowCount);
        var $procosoldqty       = $row.find('#procosoldqty'+rowCount);
        var $procortnqty        = $row.find('#procortnqty'+rowCount);
        var $procoshortqty      = $row.find('#procoshortqty'+rowCount);
        var $procooverqty       = $row.find('#procooverqty'+rowCount);
        var $procoadjqty        = $row.find('#procoadjqty'+rowCount);
        var $procobalqty        = $row.find('#procobalqty'+rowCount);
        var $procosamt          = $row.find('#procosamt'+rowCount);
        var $begbal             = $row.find('#begbal'+rowCount);
        
        if ( $('#prococode1:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#prococode'+rowCount).autocomplete({
                source: 'item-data.php',
                minLength: 0,
                select: function(event, ui) {
                    $prococode.val(ui.item.rm_code);
                    //$procodesc.val(ui.item.desc);
                  	//$procouom.val(ui.item.uom);

                    getUprice(rowCount);
                    // Give focus to the next input field to recieve input from user
                    $procosoldqty.focus();

                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.rm_code + " - " + item.desc + "</a>" )
                    .appendTo( ul );
            };
          	
			// Add row after the first row in table
            $seqno.val(rowCount);
            
            $('.item-row:last', $itemsTable).after($row);
            $($prococode).focus();

        } // End if last itemCode input is empty
        return false;
    });
    
   
    $('#itemCode').focus(function(){
     //   window.onbeforeunload = function(){ return "You haven't saved your data.  Are you sure you want to leave this page without saving first?"; };
    });
    

}); // End DOM

	