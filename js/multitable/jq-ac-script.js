$(document).ready(function(){
		var idt = 2;
    // Use the .autocomplete() method to compile the list based on input from user
    $('#procomat').autocomplete({
        source: 'item-data.php',
        minLength: 0,
        select: function(event, ui) {
            var $itemrow = $(this).closest('tr');
                    // Populate the input fields from the returned values
                    $itemrow.find('#procomat').val(ui.item.rm_code);
                    $itemrow.find('#itemDesc').val(ui.item.itemDesc);
                    $itemrow.find('#itemPrice').val(ui.item.itemPrice);

                    // Give focus to the next input field to recieve input from user
                    $('#procoucost').focus();

            return false;
	    }
    // Format the list menu output of the autocomplete
    }).data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a>" + item.rm_code + " - " + item.colour + "</a>" )
            .appendTo( ul );
    };

    // Get the table object to use for adding a row at the end of the table
    var $itemsTable = $('#itemsTable');

    // Create an Array to for the table row. ** Just to make things a bit easier to read.
    
    var rowTemp = [
        '<tr class="item-row">',
            '<td><a id="deleteRow"><img src="../images/icon-minus.png" alt="Remove Item" title="Remove Item"></a></td>',
            '<td style="width: 30px"><input name="seqno[]" class="tInput" id="seqno" readonly="readonly" style="width: 30px"></td>',
            '<td style="width: 150px"><input name="procomat[]" value="" class="tInput" id="procomat" tabindex="1"></td>',
            '<td style="width: 250px"><input name="procodesc[]" value="" class="tInput" id="procodesc" readonly="readonly" style="width: 250px" ></td>',
            '<td style="width: 40px"><input name="procouom[]" value="" class="tInput" id="procouom" readonly="readonly" style="width: 40px" ></td>',
            '<td style="width: 80px"><input name="procoucost[]" value="" class="tInput" id="procoucost" tabindex="2"></td>',
            '<td style="width: 80px"><input name="prococompt[]" value="" class="tInput" id="prococompt" tabindex="3"></td>',
            '<td style="width: 100px"><input name="prococost[]" value="" class="tInput" id="prococost" readonly="readonly" style="width: 100px" ></td>',
            '<td style="width: 80px"><input name="procomark[]" value="" class="tInput" id="procomark" readonly="readonly" style="width: 80px"> </td>',
            '<td style="width: 80px"><input name="procospre[]" value="" class="tInput" id="procospre" readonly="readonly" style="width: 80px"> </td>',
            '<td style="width: 80px"><input name="prococut[]" value="" class="tInput" id="prococut" readonly="readonly" style="width: 80px"> </td>',
            '<td style="width: 80px"><input name="procobund[]" value="" class="tInput" id="procobund" readonly="readonly" style="width: 80px"> </td>',
        '</tr>'
    ].join('');

    // Add row to list and allow user to use autocomplete to find items.
    $("#addRow").bind('click',function(){
    
    
        var $row = $(rowTemp);

        // save reference to inputs within row
        var $seqno  	        = $row.find('#seqno');
        var $procomat 	        = $row.find('#procomat');
        var $procodesc 	        = $row.find('#procodesc');
        var $procouom	        = $row.find('#procouom');
        var $procoucost	        = $row.find('#procoucost');
        var $prococompt	        = $row.find('#prococompt');
        var $prococost	        = $row.find('#prococost');
        var $procomark	        = $row.find('#procomark');
        var $procospre	        = $row.find('#procospre');
        var $prococut	        = $row.find('#prococut');
        var $procobund	        = $row.find('#procobund');

        if ( $('#procomat:last').val() !== '' ) {

            // apply autocomplete widget to newly created row
            $row.find('#procomat').autocomplete({
                source: 'item-data.php',
                minLength: 0,
                select: function(event, ui) {
                    $procomat.val(ui.item.rm_code);
                    $itemDesc.val(ui.item.itemDesc);
                    $itemPrice.val(ui.item.itemPrice);

                    // Give focus to the next input field to recieve input from user
                    $itemQty.focus();

                    return false;
                }
            }).data( "autocomplete" )._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.rm_code + " - " + item.colour + "</a>" )
                    .appendTo( ul );
            };
          
			// Add row after the first row in table
            $seqno.val(idt++);
            
            $('.item-row:last', $itemsTable).after($row);
            $($procoucost).focus();

        } // End if last itemCode input is empty
        return false;
    });
    
    // Remove row when clicked
	$("#deleteRow").live('click',function(){
		$(this).parents('.item-row').remove();
			
		idt--;
        // Hide delete Icon if we only have one row in the list.
        if ($(".item-row").length < 2) $("#deleteRow").hide();
	});


    $('#itemCode').focus(function(){
     //   window.onbeforeunload = function(){ return "You haven't saved your data.  Are you sure you want to leave this page without saving first?"; };
    });
    

}); // End DOM

	