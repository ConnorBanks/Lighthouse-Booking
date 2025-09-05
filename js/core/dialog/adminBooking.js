$( function() {
    dialog = $( "#add-form" ).dialog({
        display: 'block',
        width: 500,
        modal: true,
        resizable: false,
        autoOpen: false,
        draggable: false,
        closeText: ""
    });

    $('#add-booking').click(function() {
         $('#add-form').dialog('open');
        //                  return false;
     });

    dialog = $( "#add-table" ).dialog({
        display: 'block',
        width: 600,
        modal: true,
        resizable: false,
        autoOpen: false,
        draggable: false,
        closeText: ""
    });

    $('#btn-add-table').click(function() {
         $('#add-table').dialog('open');
        //                  return false;
     });

    dialog = $( "#availability-form" ).dialog({
        display: 'block',
        width: 800,
        modal: true,
        resizable: false,
        autoOpen: true,
        draggable: false,
        closeText: ""
    });

    dialog = $( "#booking-form" ).dialog({
        display: 'block',
        width: 800,
        modal: true,
        resizable: false,
        autoOpen: true,
        draggable: false,
        closeText: ""
    });


} );

//    $( "#add-booking" ).button().on( "click", function() {
        //dialog.dialog( "open" );
    //});
