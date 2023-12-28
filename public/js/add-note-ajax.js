$(document).ready(function () {
    $('#addNoteForm').on('submit', function (e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');


        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            success: function (response) {
                if (response.success) {
                    var currentURL = window.location.href;

                    var parsedURL = new URL(currentURL);

                    var prefix = parsedURL.origin;
                    console.log('Заметка успешно добавлена:', response.data);
                    $('#addNoteModal').modal('hide'); // Закрыть модальное окно после успешного добавления
                    var newButton = $('<button>')
                        .addClass('list-group-item list-group-item-action')
                        .attr('data-toggle', 'modal')
                        .attr('data-target', '#editNoteModal' + response.data.id)
                        .attr('data-note-id', response.data.id)
                        .text(response.data.title);

                    var newModal = $('<div>')
                        .addClass('modal fade')
                        .attr('id', 'editNoteModal' + response.data.id)
                        .attr('tabindex', '-1')
                        .attr('role', 'dialog')
                        .attr('aria-labelledby', 'editNoteModalLabel')
                        .attr('aria-hidden', 'true')
                        .html(`
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editNoteModalLabel">Редактирование заметки</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="${prefix}/notes/${response.data.id}/update" method="POST" class="edit-form">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <input type="hidden" name="_method" value="PUT">
                                            <div class="form-group">
                                                <label for="title">Заголовок</label>
                                                <input type="text" class="form-control" id="title" name="title" value="${response.data.title}">
                                            </div>
                                            <div class="form-group">
                                                <label for="content">Содержание</label>
                                                <textarea class="form-control" id="content" name="content">${response.data.content}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary save-note-btn">Сохранить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        `);

                    $('.list-group').append(newButton);
                    $('body').append(newModal);
                } else {
                    // Обработка ошибок, если необходимо
                    console.error('Ошибка при добавлении заметки:', response.error);
                }
            },
            error: function (xhr, status, error) {
                // Обработка ошибок AJAX-запроса
                console.error('Ошибка AJAX:', error);
            }
        });
    });
});

