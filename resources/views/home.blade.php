@extends('adminlte::page')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">Все мои заметки</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="list-group">
                            @foreach($notes as $note)
                                <button class="list-group-item list-group-item-action" data-toggle="modal"
                                        data-target="#editNoteModal{{ $note->id }}"
                                        data-note-id="{{ $note->id }}">{{ $note->title }}</button>

                                <!-- Модальное окно для редактирования -->
                                <div class="modal fade" id="editNoteModal{{ $note->id }}" tabindex="-1" role="dialog"
                                     aria-labelledby="editNoteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editNoteModalLabel">Редактирование
                                                    заметки</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Форма для редактирования заметки -->
                                                <form action="{{ route('update.note', ['id' => $note->id]) }}"
                                                      method="POST" class="edit-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="title">Заголовок</label>
                                                        <input type="text" class="form-control" id="title" name="title"
                                                               value="{{ $note->title }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="content">Содержание</label>
                                                        <textarea class="form-control" id="content"
                                                                  name="content">{{ $note->content }}</textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary save-note-btn">
                                                        Сохранить
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNoteModal">
            Добавить заметку
        </button>
        <!-- Модальное окно для добавления новой заметки -->
        <div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="addNoteModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNoteModalLabel">Добавление заметки</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addNoteForm" action="{{ route('add.note') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="newTitle">Заголовок</label>
                                <input type="text" class="form-control" id="newTitle" name="title">
                            </div>
                            <div class="form-group">
                                <label for="newContent">Содержание</label>
                                <textarea class="form-control" id="newContent" name="content"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Добавить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/note-ajax.js') }}"></script>
    <script src="{{ asset('js/add-note-ajax.js') }}"></script>
@endpush

