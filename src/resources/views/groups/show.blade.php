@extends('layouts.app')

@section('content')
<div id="app" class="container">
    <div class="row">
        <div class="col">
            <group-app :group="{{ $group }}"></group-app>
        </div>
    </div>
</div>
@endsection
