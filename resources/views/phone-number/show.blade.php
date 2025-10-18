@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Phone Number #{{ $phoneNumber->id }}</h3>
        <div class="box-tools pull-right">
            <a href="{{ route('phone-number.edit', $phoneNumber) }}" class="btn btn-primary">
                <i class="fas fa-pencil-alt"></i> Edit
            </a>
        </div>
    </div>
    
    <div class="box-body">
        <table class="table table-striped table-bordered detail-view">
            <tbody>
                <tr>
                    <th style="width: 200px;">ID</th>
                    <td>{{ $phoneNumber->id }}</td>
                </tr>
                <tr>
                    <th>Number</th>
                    <td>{{ $phoneNumber->number }}</td>
                </tr>
                <tr>
                    <th>User</th>
                    <td>
                        @if($phoneNumber->user)
                            <a href="{{ route('user.show', $phoneNumber->user) }}">
                                {{ $phoneNumber->user->first_name }} {{ $phoneNumber->user->last_name }} (ID: {{ $phoneNumber->user->id }})
                            </a>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>{{ $phoneNumber->type_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>IMEI</th>
                    <td>{{ $phoneNumber->imei ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Is Valid</th>
                    <td>
                        @if($phoneNumber->is_valid)
                            <span class="text-success"><i class="fas fa-check"></i> Yes</span>
                        @else
                            <span class="text-danger"><i class="fas fa-times"></i> No</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Is Primary</th>
                    <td>
                        @if($phoneNumber->is_primary)
                            <span class="text-success"><i class="fas fa-check"></i> Yes</span>
                        @else
                            <span class="text-danger"><i class="fas fa-times"></i> No</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Is Active</th>
                    <td>
                        @if($phoneNumber->is_active)
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $phoneNumber->created_at->format('M d, Y h:i a') }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $phoneNumber->updated_at->format('M d, Y h:i a') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="box-footer">
        <a href="{{ route('phone-number.send-sms', $phoneNumber) }}" class="btn btn-warning">
            <i class="fas fa-sms"></i> Send SMS
        </a>
        <a href="{{ route('phone-number.super-update', $phoneNumber) }}" class="btn btn-info">
            <i class="fas fa-cog"></i> Super Update
        </a>
        <a href="{{ route('phone-number.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>
@endsection
