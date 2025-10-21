@extends('layouts.app')

@section('title', 'Message Send Dashboard - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Message Send Dashboard</h1>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <span>{{ $title ?? 'Message Send Dashboard' }}</span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4 col-sm-6">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="nav-item">
                            <a class="nav-link active" href="#all" aria-controls="all" role="tab" data-bs-toggle="tab">All</a>
                        </li>
                        <li role="presentation" class="nav-item">
                            <a class="nav-link" href="#state" aria-controls="state" role="tab" data-bs-toggle="tab">State</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="all">
                            <br>
                            <table class="table table-condensed">
                                <tr>
                                    <td>All users with plan</td>
                                    <td>
                                        <a href="{{ route('sms-pool.send-to-all') }}" class="btn btn-sm btn-warning">Send</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin-message-log.index', ['objType' => \App\Models\System\AdminMessageLog::TO_ALL]) }}" 
                                           class="btn btn-sm btn-success JS__load_in_modal">Log</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div role="tabpanel" class="tab-pane" id="state">
                            <br>
                            <table class="table table-condensed">
                                @foreach($byState as $stateStatistic)
                                    @if(!empty($stateStatistic->state))
                                        <tr>
                                            <td>{{ $stateStatistic->state }}</td>
                                            <td>
                                                <a href="{{ route('sms-pool.send-to-state', ['state' => $stateStatistic->state]) }}" 
                                                   class="btn btn-sm btn-warning">Send</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin-message-log.index', ['objType' => \App\Models\System\AdminMessageLog::TO_STATE, 'objId' => $stateStatistic->state]) }}" 
                                                   class="btn btn-sm btn-success JS__load_in_modal">Log</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel {
    margin-bottom: 20px;
    background-color: #fff;
    border: 1px solid transparent;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.panel-default {
    border-color: #ddd;
}
.panel-heading {
    padding: 10px 15px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    background-color: #f5f5f5;
    border-color: #ddd;
}
.panel-body {
    padding: 15px;
}
.nav-tabs {
    border-bottom: 1px solid #ddd;
}
.nav-tabs > li {
    position: relative;
    display: inline-block;
}
.nav-tabs > li > a {
    position: relative;
    display: block;
    padding: 10px 15px;
    color: #555;
    text-decoration: none;
    background-color: transparent;
    border: 1px solid transparent;
    border-radius: 4px 4px 0 0;
}
.nav-tabs > li > a:hover {
    border-color: #eee #eee #ddd;
    background-color: #eee;
}
.nav-tabs > li.active > a,
.nav-tabs > li.active > a:hover,
.nav-tabs > li.active > a:focus {
    color: #555;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
}
.tab-content {
    padding: 0;
}
.tab-pane {
    display: none;
}
.tab-pane.active {
    display: block;
}
.table-condensed > thead > tr > th,
.table-condensed > tbody > tr > th,
.table-condensed > tfoot > tr > th,
.table-condensed > thead > tr > td,
.table-condensed > tbody > tr > td,
.table-condensed > tfoot > tr > td {
    padding: 5px;
}
</style>

<script>
// Bootstrap tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.nav-tabs a[data-bs-toggle="tab"]');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs and panes
            document.querySelectorAll('.nav-tabs .active').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-pane.active').forEach(pane => pane.classList.remove('active'));
            
            // Add active class to clicked tab
            this.parentElement.classList.add('active');
            
            // Add active class to corresponding pane
            const targetPane = document.querySelector(this.getAttribute('href'));
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
});
</script>
@endsection
