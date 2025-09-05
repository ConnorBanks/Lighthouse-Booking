// Call jQuery datepicker ui //

$( function() {
    $( "#PartyDate" ).datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        hideIfNoPrevNext: true
    });

    $( "#FromDate" ).datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        hideIfNoPrevNext: true
    });

    $( "#ToDate" ).datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        hideIfNoPrevNext: true
    });

    $( "#SearchDate" ).datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        hideIfNoPrevNext: true
    });
} );
