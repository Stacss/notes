// Функция обработки отправки формы редактирования заметки
$(document).ready(function() {
    $('.edit-form').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var modal = $(this).closest('.modal');

        $.ajax({
            type: 'PUT',
            url: $(this).attr('action'),
            data: formData,
            success: function(response) {
                if (response.success) {
                    var noteId = response.data.id;
                    var newTitle = response.data.title;

                    $(`li[data-note-id="${noteId}"]`).text(newTitle);

                    var alertMessage = $('<div class="alert alert-success" role="alert">Запись изменена</div>');

                    $('.card-body').prepend(alertMessage);

                    setTimeout(function() {
                        alertMessage.fadeOut('slow', function() {
                            $(this).remove();
                        });
                    }, 3000);

                    modal.modal('hide');
                } else {

                    $('#resultMessage').text('Ошибка при обновлении заметки: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                var alertMessage = $('<div class="alert alert-success" role="alert">Запись изменена</div>');

                $('.card-body').prepend(alertMessage);

                setTimeout(function() {
                    alertMessage.fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    });
});

