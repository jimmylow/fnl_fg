$(document).ready(function(){
		
    // Use the .autocomplete() method to compile the list based on input from user
    $('#procofrm1').autocomplete({
        source: 'trf-data.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#procofrm1').val(ui.item.prcode);
                    $itemrow.find('#procofdesc1').val(ui.item.prdesc);

                    // Give focus to the next input field to recieve input from user
                    //$('#issueqtyid1').focus();
                    $('#issueqtyid1').focus();

            return false;
	    }
    // Format the list menu output of the autocomplete
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.prcode + " - " + item.prdesc + "</a>" )
            .appendTo( ul );
    };

    // Use the .autocomplete() method to compile the list based on input from user
  /*  $('#procoto1').autocomplete({
        source: 'trf-data.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#procoto1').val(ui.item.prcode);
                    $itemrow.find('#procotdesc1').val(ui.item.prdesc);

                    // Give focus to the next input field to recieve input from user
                    $('#issueqtyid1').focus();

            return false;
	    }  
    // Format the list menu output of the autocomplete
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.prcode + " - " + item.prdesc + "</a>" )
            .appendTo( ul );
    };
    */

    // Get the table object to use for adding a row at the end of the table
    var $itemsTable = $('#itemsTable');

   
    // Add row to list and allow user to use autocomplete to find items.
    $("#addRow").bind('click',function(){
    	var table = document.getElementById('itemsTable');
	    var rowCount = table.rows.length; 

    	 // Create an Array to for the table row. ** Just to make things a bit easier to read.
    var idprocofrm  = "procofrm"+rowCount;
    var idprocofdesc = "procofdesc"+rowCount;
    //var idprocoto  = "procoto"+rowCount;
    //var idprocotdesc = "procotdesc"+rowCount;
    var idissueqty  = "issueqtyid"+rowCount;
    

 //           '<td><input name="procoto[]" id="'+idprocoto+'" class="autosearch" style=" width: 100px;" onchange ="upperCase(this.id)"></td>',
 //           '<td><input name="procotdesc[]" id="'+idprocotdesc+'" readonly="readonly" style="width: 200px; border:0;"></td>',
    
    var rowTemp = [
         '<tr class="item-row">',
            '<td><input name="seqno[]" class="tInput" id="seqno" readonly="readonly" style="width: 27px; border:0;"></td>',
            '<td><input name="procofrm[]" id="'+idprocofrm+'" class="autosearch" style="width: 150px" onchange ="upperCase(this.id)"></td>',
            '<td><input name="procofdesc[]" id="'+idprocofdesc+'" readonly="readonly" style="width: 300px;  border:0;" ></td>',
            '<td><input name="issueqty[]" id="'+idissueqty+'"  style="width: 75px" onChange="onhand_checking('+rowCount+');"></td>',

        '</tr>'
    ].join('');
	
    
        var $row = $(rowTemp);

        // save reference to inputs within row
        var $seqno  	= $row.find('#seqno');
        var $procofrm 	= $row.find('#procofrm'+rowCount);
        var $procofdesc 	= $row.find('#procofdesc'+rowCount);
        //var $procoto	= $row.find('#procoto'+rowCount);
        //var $procotdesc	= $row.find('#procotdesc'+rowCount);
        var $issueqtyid	= $row.find('#issueqtyid'+rowCount);

        if ( $('#procofrm1:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#procofrm'+rowCount).autocomplete({
                source: 'trf-data.php',
                minLength: 0,
                select: function(event, ui) {
                    $procofrm.val(ui.item.prcode);
                    $procofdesc.val(ui.item.prdesc);
 	
                    // Give focus to the next input field to recieve input from user
                     $('#issueqtyid'+rowCount).focus();

                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.prcode + " - " + item.prdesc + "</a>" )
                    .appendTo( ul );
            };
            
          /*  $row.find('#procoto'+rowCount).autocomplete({
                source: 'trf-data.php',
                minLength: 0,
                select: function(event, ui) {
                    $procoto.val(ui.item.prcode);
                    $procotdesc.val(ui.item.prdesc);
 	
                    // Give focus to the next input field to recieve input from user
                     $('#issueqtyid'+rowCount).focus();

                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.prcode + " - " + item.prdesc + "</a>" )
                    .appendTo( ul );
            };  */          
          	
			// Add row after the first row in table
            $seqno.val(rowCount);
            
            $('.item-row:last', $itemsTable).after($row);
            $($procofrm).focus();

        } // End if last itemCode input is empty
        return false;
    });
    
   
    $('#itemCode').focus(function(){
     //   window.onbeforeunload = function(){ return "You haven't saved your data.  Are you sure you want to leave this page without saving first?"; };
    });
    

}); // End DOM

	