@extends('layouts.app')

@php

if ($editing) {
    Request::flash();
}

@endphp

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ $editing ? __('Update Todo') : __('Create Todo') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ $editing ? route('todos.update', ['todo' => $todo->id]) : route('todos.store') }}">
                        @csrf

                        @if($editing)
                            {{ method_field('PUT') }}
                        @endif

                        @if($errors->any())
                            <div class="alert alert-info mb-3">
                                @foreach($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="title">{{ __('Titre') }}</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $todo->title) }}"
                                   class="form-control" required placeholder="Nettoyage">
                        </div>

                        <div class="form-group">
                            <label for="description">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control" id="description" cols="30" rows="10"
                                      placeholder="Pour faire ..">{{ old('description', $todo->description) }}</textarea>
                        </div>

                        @if($editing)
                            <div class="form-group">
                                <label for="done">Is done</label>
                                <input type="checkbox" class="form-control" name="done" id="done" value="1"{{ $todo->done ? ' checked disabled' : '' }}>
                            </div>
                        @endif

                        <button class="btn btn-primary" type="submit">{{ $editing ? __('Update') : __('Save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
