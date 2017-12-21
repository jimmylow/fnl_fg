$(document).ready(function(){
		
    // Use the .autocomplete() method to compile the list based on input from user
    $('#procd1').autocomplete({
        source: 'get_pro_cd.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#procd1').val(ui.item.prod_code);
                    $itemrow.find('#proconame1').val(ui.item.prdesc);
                    $itemrow.find('#prouom1').val(ui.item.pruom);
                   
                    // Give focus to the next input field to recieve input from user
                    $('#proordqty1').focus();

            return false;
	    }
    // Format the list menu output of the autocomplete
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.prod_code + " - " + item.colour + " - " + item.size + " - " + item.prtype + "</a>" )
            .appendTo( ul );
    };


    // Get the table object to use for adding a row at the end of the table
    var $itemsTable = $('#itemsTable');

   
    // Add row to list and allow user to use autocomplete to find items.
    $("#addRow").bind('click',function(){
    	var table = document.getElementById('itemsTable');
	    var rowCount = table.rows.length; 

    	 // Create an Array to for the table row. ** Just to make things a bit easier to read.
    var idprocd = "procd"+rowCount;
    var idproname = "proconame"+rowCount;
    var idproordqty = "proordqty"+rowCount;
    var idprouom = "prouom"+rowCount;
    var idprooupri = "prooupri"+rowCount;
    var idproouamt = "proouamt"+rowCount;
    var rowTemp = [
          '<tr class="item-row">',
          '<td><input name="seqno[]" id="seqno" value="1" readonly="readonly" style="width: 27px; border:0;"></td>',
          '<td><input name="procd[]" value="" tProCd'+rowCount+'="1" id="'+idprocd+'" class="autosearch" style="width: 175px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '+rowCount+')"></td>',
          '<td><input name="procdname[]" value="" class="tInput" id="'+idproname+'" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>',
          '<td><input name="proorqty[]" id="'+idproordqty+'" onBlur="calcAmt('+rowCount+');" style="width: 97px; text-align:center;"></td>',
          '<td><input name="prouom[]"  id="'+idprouom+'" readonly="readonly" style="width: 75px; border-style: none; border-color: inherit; border-width: 0;"></td>',
		  '<td><input name="prooupri[]" id="'+idprooupri+'" onBlur="calcAmt('+rowCount+');" style="width: 89px; text-align:right;"></td>',
		  '<td><input name="proouamt[]" id="'+idproouamt+'" style="width: 116px; border-style: none; border-color: inherit; border-width: 0; text-align:right;">	</td>',
          '</tr>'
    ].join('');
	
    
        var $row = $(rowTemp);

        // save reference to inputs within row
        var $seqno  	        = $row.find('#seqno');
        var $procd   	        = $row.find('#procd'+rowCount);
        var $proconame	        = $row.find('#proconame'+rowCount);
        var $proorqty	        = $row.find('#proorqty'+rowCount);
        var $prouom             = $row.find('#prouom'+rowCount);
        var $prooupri	        = $row.find('#prooupri'+rowCount);
        var $proouamt	        = $row.find('#proouamt'+rowCount);
       
       if ( $('#procd1:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#procd'+rowCount).autocomplete({
                source: 'get_pro_cd.php',
                minLength: 0,
                select: function(event, ui) {
                    $procd.val(ui.item.prod_code);
                    $proconame.val(ui.item.prdesc);
                  	$prouom.val(ui.item.pruom);
                    	
                    // Give focus to the next input field to recieve input from user
                    //$proorqty.focus();
                    $('#proordqty'+rowCount).focus();

                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.prod_code + " - " + item.colour + " - " + item.size + " - " + item.prtype + "</a>" )
                    .appendTo( ul );
            };
          	
			// Add row after the first row in table
            $seqno.val(rowCount);
           
            $('.item-row:last', $itemsTable).after($row);
            $($proorqty).focus();

        } // End if last itemCode input is empty
        return false;
    });
    
   
    $('#itemCode').focus(function(){
     //   window.onbeforeunload = function(){ return "You haven't saved your data.  Are you sure you want to leave this page without saving first?"; };
    });
    

}); // End DOM

	