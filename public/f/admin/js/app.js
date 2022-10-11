$('.labelauty').labelauty({ class: 'custom-labelauty' });
$('.labelauty-reverse').labelauty({ class: 'labelauty' });
$('.c-file-input').on('change', function(){
    var $this = $(this),
        files = this.files,
        filesLength = files.length,
        label;

    if (filesLength===0) label = $this.data('original-title');
    else if (filesLength===1) label = files[0].name;
    else if (filesLength===2) label = files[0].name+', '+files[1].name;
    // else if (filesLength<=3) {
    //     label = '';
    //     for (var i=0; i<filesLength; i++) {
    //         if (i!==0) label+=', ';
    //         label += files[i].name;
    //     }
    // }
    else label = 'Выбрано '+filesLength.toString()+' файла';
    $this.next('.c-file-label').text(label);
});
$('#logout-user').on('click', function(){
    $('<form style="display:none" action="'+$(this).attr('data-action')+'" method="post"><input type="hidden" name="action" value="logout"></form>').appendTo(document.body).submit();
});
//region Sortable
var sortableUpdate = function(self, action){
    var data = [],
        trs = self.children();
    $.each(trs, function(index, elem){
        var id = parseInt($(elem).data('id'));
        if (id>0) data.push(id);
    });
    if (data.length>0) $.ajax({
        type:'post',
        url:action,
        dataType:'json',
        data: {
            _token:csrf,
            _method:'patch',
            data: data
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });
};
$('.table-sortable').each(function(index,table) {
    var $table = $(table),
        action = $table.data('action');
    if (action) $table.sortable({
        axis:'y',
        helper: function(e, tr)
        {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index)
            {
                // Set helper cell sizes to match the original sizes
                $(this).width($originals.eq(index).outerWidth());
            });
            return $helper;
        },
        update: function(){
            sortableUpdate($(this), action)
        }
    });
});
//endregion
