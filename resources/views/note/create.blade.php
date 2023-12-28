@extends('adminlte::page')

@section('content')
    <div class="container">
        <form action="{{ route('create.note') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Добавить записку</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea class="form-control" id="content" name="content"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection


