@extends('layouts.app')

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Contact Request #{{ $contact->id }}</h3>
    </div>
    
    <div class="box-body">
        <table class="table table-striped table-bordered detail-view">
            <tbody>
                <tr>
                    <th style="width: 200px;">ID</th>
                    <td>{{ $contact->id }}</td>
                </tr>
                @if($contact->id_user)
                    <tr>
                        <th>ID User</th>
                        <td>{{ $contact->id_user }}</td>
                    </tr>
                @endif
                <tr>
                    <th>Name</th>
                    <td>{{ $contact->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                </tr>
                <tr>
                    <th>Subject</th>
                    <td>{{ $contact->subject ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td>
                        <div style="white-space: pre-wrap;">{{ $contact->body }}</div>
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($contact->status == 1)
                            <span class="badge bg-warning">New</span>
                        @elseif($contact->status == 2)
                            <span class="badge bg-success">Replied</span>
                        @else
                            <span class="badge bg-default">Closed</span>
                        @endif
                    </td>
                </tr>
                @if($contact->user)
                    <tr>
                        <th>User</th>
                        <td>
                            <a href="{{ route('user.show', $contact->user) }}">
                                {{ $contact->user->first_name }} {{ $contact->user->last_name }} (ID: {{ $contact->user->id }})
                            </a>
                        </td>
                    </tr>
                @endif
                <tr>
                    <th>Created At</th>
                    <td>{{ $contact->created_at->format('M d, Y h:i a') }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $contact->updated_at->format('M d, Y h:i a') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="box-footer">
        @if($contact->status == 1)
            <form action="{{ route('contact.mark-replied', $contact) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Mark as Replied
                </button>
            </form>
        @endif
        <a href="{{ route('contact.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>
@endsection
