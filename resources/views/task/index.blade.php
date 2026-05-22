@extends('layouts.app')
@section('title','Task')
@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">{{__('Manage') .' '.__('Task')}}</h3>
    </div>

    <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
            <div id="message"></div>
        </div>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="">

                    <div class="form-group mb-4">
                        <label for="">Task</label>
                        <input type="text" class="form-control" placeholder="Task">
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

dd(1);
@endsection