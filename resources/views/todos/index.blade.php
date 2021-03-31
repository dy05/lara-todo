@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Todos List') }}</div>
                <div class="card-body">
                    @if(Session::has('success'))
                        <div class="alert alert-info mb-3">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <div class="row justify-content-center">
                        @foreach($todos as $todo)
                            <div class="col-md-8 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        {{ $todo->title }}
                                    </div>
                                    <div class="card-body">
                                        {{ $todo->description }}
                                    </div>
                                    <div class="card-footer">
                                        <div class="justify-content-around">
                                            <span class="badge {{ $todo->done ? 'badge-info' : 'badge-secondary' }}">
                                                {{ $todo->done ? __('Done') : __('Undone') }}
                                            </span>
                                            <hr>
                                            <div class="d-flex">
                                                <a class="btn btn-dark" href="{{ route('todos.show', ['todo' => $todo->id]) }}">{{ __('View') }}</a>
                                                @if($todo->user_id == Auth::id())
                                                    <a class="ml-3 btn btn-primary" href="{{ route('todos.edit', ['todo' => $todo->id]) }}">{{ __('Edit') }}</a>
                                                    <form action="{{ route('todos.destroy', ['todo' => $todo->id]) }}">
                                                        @csrf
                                                        {{ method_field('DELETE') }}
                                                        <button class="ml-3 btn btn-danger" type="submit">{{ __('Delete') }}</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-md-8 mt-4">
                            {{ $todos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
