;$(function() {
    $('a.btn-delete').click(function(event) {
        event.preventDefault();
        var link = $(this);
        var text = link.attr('msg');
        var href = link.attr('action');
        var modal = $('#myModal');
        modal.modal();
        modal.find('form').attr('action', href);
        modal.find('.modal-body').text(text);
    });
})