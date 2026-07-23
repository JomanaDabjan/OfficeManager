@extends('layouts.app')

@section('Main_Content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Dashboard </h4>
                </div>
                <div class="card-body">
                    <p class="text-dark font-weight-bold">You're logged in! hi {{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
