@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Terminate Contract Line #{{ $contractLine->id }}</h3>
    </div>
    
    <form action="{{ route('user.terminate', $contractLine) }}" method="POST">
        @csrf
        
        <div class="box-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Warning:</strong> You are about to terminate this contract line. This action cannot be undone.
            </div>

            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 200px;">Contract Line ID</th>
                        <td>{{ $contractLine->id }}</td>
                    </tr>
                    <tr>
                        <th>User ID</th>
                        <td>{{ $contractLine->id_user }}</td>
                    </tr>
                    <tr>
                        <th>Title</th>
                        <td>{{ $contractLine->title ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($contractLine->terminated_at)
                                <span class="label label-danger">Terminated on {{ $contractLine->terminated_at }}</span>
                            @else
                                <span class="label label-success">Active</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $contractLine->created_at->format('M d, Y h:i a') }}</td>
                    </tr>
                </tbody>
            </table>

            @if($contractLine->terminated_at)
                <div class="alert alert-info">
                    This contract line is already terminated.
                </div>
            @endif
        </div>
        
        <div class="box-footer">
            @if(!$contractLine->terminated_at)
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to terminate this contract line?');">
                    <i class="fas fa-ban"></i> Terminate Contract
                </button>
            @endif
            <a href="{{ route('user.show', $contractLine->id_user) }}" class="btn btn-default">
                <i class="fas fa-arrow-left"></i> Back to User
            </a>
        </div>
    </form>
</div>
@endsection
