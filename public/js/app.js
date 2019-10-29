$(document).ready(function() {
    $('.js-remove-link').on('click', function (event) {
        event.preventDefault();
        if (confirm('Точно удалить?')) {
            console.log(event.target.href);
            $.ajax({
                method: 'DELETE',
                url: event.target.href
            }).done(function (data) {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.error);
                }
            });
        }
    })
});
