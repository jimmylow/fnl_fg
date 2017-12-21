$(document).ready(function(){
		
    var speauth;
    
    // Use the .autocomplete() method to compile the list based on input from user
    $('#prococode1').autocomplete({
        source: 'item-data.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#prococode1').val(ui.item.rm_code);
                    //$itemrow.find('#procodesc').val(ui.item.desc);
                    $itemrow.find('#procouom1').val(ui.item.uom);

            speauth = ui.item.auth;

                    getUprice(1);
                    // Give focus to the next input field to recieve input from user
                    $('#procoqty1').focus();

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
    var idprocoprice = "procoprice"+rowCount;
    var idprocoqty = "procoqty"+rowCount;    
    var idprocotype = "procotype"+rowCount;
    var idprocouom = "procouom"+rowCount;
    var idprocouqty = "procouqty"+rowCount;
    var idprocototpcs = "procototpcs"+rowCount;
    
    var speauthtxt;
    if (speauth == "N") { speauthtxt = 'readonly="readonly"'; } else  { speauthtxt = ''; }
    
    var rowTemp = [
         '<tr class="item-row">',
            '<td><input name="seqno[]" class="tInput" id="seqno" readonly="readonly" style="width: 27px; border:0;"></td>',
            '<td><input name="prococode[]" value="" tProItem'+rowCount+'="1" id="'+idprococode+'" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)"></td>',
            '<td><input name="procouom[]" value="" class="tInput" id="'+idprocouom+'" style="border-style: none; border-color: inherit; border-width: 0; width: 60px"></td>',
            '<td><input name="procouqty[]" value="" class="tInput" id="'+idprocouqty+'" style="border-style: none; width: 48px; text-align : right"></td>',
            '<td><input name="procotype[]" value="" class="tInput" id="'+idprocotype+'" style="border-style: none; border-color: inherit; border-width: 0; width: 75px" '+speauthtxt+' ></td>',
            '<td><input name="procoprice[]" value="" class="tInput" id="'+idprocoprice+'" style="border-style: none; border-color: inherit; border-width: 0; width: 75px; text-align : right" '+speauthtxt+' ></td>',
            '<td><input name="procoqty[]" value="" class="tInput" id="'+idprocoqty+'" style="width: 48px; text-align : right" onBlur="get_totpcs('+rowCount+');" ></td>',
            '<td><input name="procototpcs[]" value="" class="tInput" id="'+idprocototpcs+'" style="border-style: none; width: 48px; text-align : right" ></td>',
        '</tr>'
    ].join('');
	
    
        var $row = $(rowTemp);

        // save reference to inputs within row
        var $seqno  	        = $row.find('#seqno');
        var $prococode 	        = $row.find('#prococode'+rowCount);
        var $procouom	          = $row.find('#procouom'+rowCount);
        var $procouqty          = $row.find('#procouqty'+rowCount);
        var $procotype	        = $row.find('#procotype'+rowCount);             
        var $procoprice	        = $row.find('#procoprice'+rowCount);        
        var $procoqty 	        = $row.find('#procoqty'+rowCount);
        var $procototpcs	      = $row.find('#procototpcs'+rowCount);

        if ( $('#prococode1:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#prococode'+rowCount).autocomplete({
                source: 'item-data.php',
                minLength: 0,
                select: function(event, ui) {
                    $prococode.val(ui.item.rm_code);
                    //$procodesc.val(ui.item.desc);
                  	$procouom.val(ui.item.uom);

                    getUprice(rowCount);
                    // Give focus to the next input field to recieve input from user
                    $procoqty.focus();

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

	