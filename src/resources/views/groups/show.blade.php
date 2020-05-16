@extends('layouts.app')

@section('content')
<div id="app" class="container">
    <div class="row">
        <div class="col">
            <task-list :group="{{ $group }}"></task-list>
        </div>
    </div>
</div>
@endsection
