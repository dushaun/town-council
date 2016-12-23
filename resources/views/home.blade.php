@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <admin-panel></admin-panel>
        </div>
        <div class="col-md-6">
            <public-queue></public-queue>
        </div>
    </div>
</div>
@endsection
