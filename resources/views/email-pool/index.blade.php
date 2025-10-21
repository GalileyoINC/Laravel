@extends('layouts.app')

@section('title', 'Email Pools - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Email Pools</h3>
                    <div class="panel-heading-actions">
                        <a href="{{ route('email-pool-archive.index') }}" class="btn btn-info">
                            <i class="fas fa-archive"></i> Archive
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    @php
                    use App\Helpers\TableFilterHelper;
                    @endphp

                    <div class="mb-3">
                        <a href="{{ route('email-pool.export', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                        <a href="{{ route('email-pool-archive.index') }}" class="btn btn-info">
                            <i class="fas fa-archive"></i> Archive
                        </a>
                    </div>

                    <x-table-filter 
                        :title="'Email Pools'" 
                        :data="$emailPools"
                        :columns="[
                            TableFilterHelper::textColumn('ID', 'ID', 'grid__id'),
                            TableFilterHelper::selectColumn('Type', ['immediate' => 'Immediate', 'later' => 'Later', 'background' => 'Background']),
                            TableFilterHelper::selectColumn('Status', ['pending' => 'Pending', 'sent' => 'Sent', 'failed' => 'Failed', 'cancelled' => 'Cancelled']),
                            TableFilterHelper::textColumn('From'),
                            TableFilterHelper::textColumn('To'),
                            TableFilterHelper::textColumn('Reply'),
                            TableFilterHelper::textColumn('BCC'),
                            TableFilterHelper::textColumn('Subject'),
                            TableFilterHelper::textColumn('Created At'),
                            TableFilterHelper::clearButtonColumn('Actions', 'action-column-2'),
                        ]"
                    >
                        @forelse($emailPools as $emailPool)
                            @php
                                $idVal = is_array($emailPool) ? ($emailPool['id'] ?? '') : ($emailPool->id ?? '');
                                $type = is_array($emailPool) ? ($emailPool['type'] ?? '') : ($emailPool->type ?? '');
                                $status = is_array($emailPool) ? ($emailPool['status'] ?? '') : ($emailPool->status ?? '');
                                $fromVal = is_array($emailPool) ? ($emailPool['from'] ?? '') : ($emailPool->from ?? '');
                                $toRaw = is_array($emailPool) ? ($emailPool['to'] ?? '') : ($emailPool->to ?? '');
                                $toArray = is_string($toRaw) ? json_decode($toRaw, true) : (is_array($toRaw) ? $toRaw : []);
                                $toEmails = is_array($toArray) ? array_keys($toArray) : [];
                                $replyVal = is_array($emailPool) ? ($emailPool['reply'] ?? '') : ($emailPool->reply ?? '');
                                $bccVal = is_array($emailPool) ? ($emailPool['bcc'] ?? '') : ($emailPool->bcc ?? '');
                                $subjectVal = is_array($emailPool) ? ($emailPool['subject'] ?? '') : ($emailPool->subject ?? '');
                                $created = is_array($emailPool) ? ($emailPool['created_at'] ?? null) : ($emailPool->created_at ?? null);
                            @endphp
                            <tr class="data-row">
                                <td @dataColumn(0)>{{ $idVal }}</td>
                                <td @dataColumn(1) @dataValue($type)>
                                    @if($type === 'immediate')
                                        <span class="badge bg-success">Immediate</span>
                                    @elseif($type === 'later')
                                        <span class="badge bg-warning">Later</span>
                                    @elseif($type === 'background')
                                        <span class="badge bg-info">Background</span>
                                    @else
                                        <span class="badge bg-default">{{ ucfirst($type) }}</span>
                                    @endif
                                </td>
                                <td @dataColumn(2) @dataValue($status)>
                                    @if($status === 'sent')
                                        <span class="badge bg-success">Sent</span>
                                    @elseif($status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($status === 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @elseif($status === 'cancelled')
                                        <span class="badge bg-secondary">Cancelled</span>
                                    @else
                                        <span class="badge bg-info">{{ ucfirst($status) }}</span>
                                    @endif
                                </td>
                                <td @dataColumn(3)>{{ $fromVal }}</td>
                                <td @dataColumn(4)>
                                    {{ implode(', ', array_slice($toEmails, 0, 3)) }}
                                    @if(count($toEmails) > 3)
                                        <span class="text-muted">(+{{ count($toEmails) - 3 }} more)</span>
                                    @endif
                                </td>
                                <td @dataColumn(5)>{{ $replyVal ?: '-' }}</td>
                                <td @dataColumn(6)>{{ $bccVal ?: '-' }}</td>
                                <td @dataColumn(7)>{{ Str::limit($subjectVal, 50) }}</td>
                                <td @dataColumn(8)>
                                    @if($created instanceof \Illuminate\Support\Carbon)
                                        {{ $created->format('M d, Y') }}
                                    @elseif(!empty($created))
                                        {{ \Illuminate\Support\Carbon::parse($created)->format('M d, Y') }}
                                    @endif
                                </td>
                                <td @dataColumn(9)>
                                    <div class="btn-group">
                                        @if($idVal)
                                        <a href="{{ route('email-pool.show', ['email_pool' => $idVal]) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <form method="POST" action="{{ route('email-pool.destroy', ['email_pool' => $idVal]) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No email pools found.</td>
                            </tr>
                        @endforelse
                    </x-table-filter>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel-default {
    border: 1px solid #ddd;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.panel-heading-actions {
    display: flex;
    gap: 10px;
}
.panel-body {
    padding: 15px;
}
.grid__id {
    width: 60px;
}
.action-column-2 {
    width: 150px;
}
.label {
    display: inline-block;
    padding: 0.25em 0.6em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}
.label-success {
    background-color: #28a745;
    color: #fff;
}
.label-warning {
    background-color: #ffc107;
    color: #212529;
}
.label-danger {
    background-color: #dc3545;
    color: #fff;
}
.label-info {
    background-color: #17a2b8;
    color: #fff;
}
.label-default {
    background-color: #6c757d;
    color: #fff;
}
.text-muted {
    color: #6c757d;
}
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}
.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}
.btn-group {
    display: inline-flex;
    vertical-align: middle;
}
.btn-group .btn {
    position: relative;
    flex: 1 1 auto;
}
.btn-group .btn + .btn {
    margin-left: -1px;
}
.btn-group .btn:first-child {
    margin-left: 0;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}
.btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}
.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.btn-success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
}
.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.form-inline .form-group {
    display: flex;
    flex: 0 0 auto;
    flex-flow: row wrap;
    align-items: center;
    margin-bottom: 0;
}
.form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
}
</style>
@endsection
