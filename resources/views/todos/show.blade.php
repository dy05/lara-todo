@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ $todo->title }}</div>
                <div class="card-body">
                    <p>
                        <span>Title : </span>
                        {{ $todo->title }}
                    </p>
                    <p>
                        <span>Description : </span>
                        {{ $todo->description }}
                    </p>
                    <p class="alert alert-info">
                        {{ $todo->done ? __('Done') : __('Undone') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
