$( function() {
  $( "#accordion" ).accordion({
    collapsible: true,
    heightStyle: "content"
  });

  $("#pastBookings").accordion({
      collapsible : true,
      active : 'none',
      heightStyle: "content"
  });
} );
