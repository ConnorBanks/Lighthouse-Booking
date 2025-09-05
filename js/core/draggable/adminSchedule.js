$( function() {
    var startPosition = 0;
    $('.calendar__row').draggable({
        axis: 'x',
        containment: ".calendar__table-row",
        cursor: "move",
        grid: [ 100, 100 ],
        drag: function( event, ui ) {
            if(ui.position.left < startPosition)
                ui.position.left = startPosition;
        }
    });

    $('.calendar').draggable({
        axis: 'x',
        cursor: "move",
        grid: [ 100, 100 ],
        drag: function( event, ui ) {
            if(ui.position.left < startPosition)
                ui.position.left = startPosition;
        }
    });
} );
