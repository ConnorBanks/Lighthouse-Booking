$( function() {
    $( ".calendar__row" ).resizable({
        containment: ".calendar__table-row",
        grid: 100,
        handles: 'e, w',
        minWidth: 160
    });
} );
