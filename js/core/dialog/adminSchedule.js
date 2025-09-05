$( function() {
    dialog = $( "#schedule-form" ).dialog({
        display: 'block',
        width: 900,
        modal: true,
        resizable: false,
        autoOpen: false,
        draggable: false,
        closeText: "",
    });

    $('.calendar__info').click(function() {
         $("#schedule-form").dialog('open');
        //                  return false;
     });
 });
