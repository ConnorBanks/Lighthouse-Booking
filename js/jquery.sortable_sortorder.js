//********************************************************************//
//******************* SORTABLE SORTORDER SETTINGS ********************//
//********************************************************************//
var settingsSortable_Sortorder__el = '.sortable_sortorder tbody';
var settingsSortable_Sortorder__url = $('.sortable_sortorder_url').val();

var settingsSortable_Sortorder__method = 'POST';

var settingsSortable_Sortorder__axis = 'y';
var settingsSortable_Sortorder__cursor = 'move';



var fixHelper = function(e, tr) {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index) {
        $(this).width($originals.eq(index).width())
    });
    return $helper;
};

$(settingsSortable_Sortorder__el).sortable({
    //placeholder: "ui-state-highlight",
    placeholder: {
        element: function(currentItem) {
            var fullWidth = currentItem.parent().width();
            var cellheight = currentItem.height();
            return $('<tr class="ui-state-highlight"><td colspan="6"><div class="ui-state-highlight" style="font-size: 20px; display: table-cell; vertical-align: middle;text-align: center; width: ' + fullWidth + 'px; height: ' + cellheight + 'px">Drop Here</div></td></tr>');
        },
        update: function(container, p) {
            return;
        }
    },
    axis: 'y',
    cursor: 'move',
    opacity: 0.5,
    items: 'tr',
    helper: fixHelper,
    update: function(event, ui) {
        var data = $(this).sortable('serialize');

        //alert(data);
        // POST to server using $.post or $.ajax

        $.ajax({
            data: data,
            type: settingsSortable_Sortorder__method,
            url: settingsSortable_Sortorder__url
        });

        var count = 1;
        $('.sortable_sortorder tbody tr').each(function() {
            //alert($(this).find(">:first-child").html());
            $(this).find(">:first-child").html(count);
            count++;
        });


    }
});
$(settingsSortable_Sortorder__el).disableSelection();