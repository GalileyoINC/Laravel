@extends('layouts.app')

@section('title', 'Maintenance - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">System Maintenance</h3>
                </div>
                <div class="panel-body">
                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4>Quick Actions</h4>
                            <div class="btn-group" role="group">
                                <a href="{{ route('maintenance.clear-cache') }}" class="btn btn-warning" onclick="return confirm('Are you sure you want to clear all cache?')">
                                    <i class="fas fa-trash"></i> Clear Cache
                                </a>
                                <a href="{{ route('maintenance.clear-logs') }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to clear all logs?')">
                                    <i class="fas fa-file-alt"></i> Clear Logs
                                </a>
                                <a href="{{ route('maintenance.database-maintenance') }}" class="btn btn-info" onclick="return confirm('Are you sure you want to run database maintenance?')">
                                    <i class="fas fa-database"></i> Database Maintenance
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <span>System Information</span>
                                </div>
                                <div class="panel-body">
                                    <p><strong>PHP Version:</strong> {{ PHP_VERSION }}</p>
                                    <p><strong>Laravel Version:</strong> {{ app()->version() }}</p>
                                    <p><strong>Server Software:</strong> {{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</p>
                                    <p><strong>Server OS:</strong> {{ PHP_OS }}</p>
                                    <p><strong>Memory Limit:</strong> {{ ini_get('memory_limit') }}</p>
                                    <p><strong>Max Execution Time:</strong> {{ ini_get('max_execution_time') }}s</p>
                                    <p><strong>Upload Max Filesize:</strong> {{ ini_get('upload_max_filesize') }}</p>
                                    <p><strong>Post Max Size:</strong> {{ ini_get('post_max_size') }}</p>
                                    <p><strong>Timezone:</strong> {{ config('app.timezone') }}</p>
                                    <p><strong>Environment:</strong> {{ config('app.env') }}</p>
                                    <p><strong>Debug Mode:</strong> {{ config('app.debug') ? 'Enabled' : 'Disabled' }}</p>
                                    <p><strong>Database Driver:</strong> {{ config('database.default') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <span>Session Management</span>
                                </div>
                                <div class="panel-body">
                                    <form id="session-form" class="row g-2 align-items-end">
                                        @csrf
                                        <div class="form-group me-2">
                                            <input type="text" name="key" class="form-control form-control-sm" placeholder="Session Key" required>
                                        </div>
                                        <div class="form-group me-2">
                                            <input type="text" name="value" class="form-control form-control-sm" placeholder="Session Value">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Set Session</button>
                                    </form>
                                    <hr>
                                    <p><strong>Current Session ID:</strong> {{ session()->getId() }}</p>
                                    <p><strong>Session Lifetime:</strong> {{ config('session.lifetime') }} minutes</p>
                                    <p><strong>Session Driver:</strong> {{ config('session.driver') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance Tools -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <span>Maintenance Tools</span>
                                </div>
                                <div class="panel-body">
                                    <ul class="list-unstyled">
                                        <li><a href="{{ route('maintenance.system-info') }}" class="btn btn-link">System Information</a></li>
                                        <li><a href="{{ route('maintenance.queue-status') }}" class="btn btn-link">Queue Status</a></li>
                                        <li><a href="{{ route('maintenance.storage-status') }}" class="btn btn-link">Storage Status</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <span>Artisan Commands</span>
                                </div>
                                <div class="panel-body">
                                    <p>Available Artisan commands for maintenance:</p>
                                    <ul>
                                        <li><code>php artisan cache:clear</code> - Clear application cache</li>
                                        <li><code>php artisan config:clear</code> - Clear configuration cache</li>
                                        <li><code>php artisan route:clear</code> - Clear route cache</li>
                                        <li><code>php artisan view:clear</code> - Clear view cache</li>
                                        <li><code>php artisan queue:work</code> - Process queued jobs</li>
                                        <li><code>php artisan migrate:status</code> - Show migration status</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- JavaScript Functions -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <span>JavaScript Functions</span>
                                </div>
                                <div class="panel-body">
                                    <p><code>core.setSession(key, value)</code> - Set data to maintenance/set-session</p>
                                    <p><code>core.showLoader()</code> <code>core.hideLoader()</code></p>
                                    <p><code>core.showAlert({type: "error", value: "Test alert"})</code> - Show alert</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <span>Queue Management</span>
                                </div>
                                <div class="panel-body">
                                    <p><strong>Queue Driver:</strong> {{ config('queue.default') }}</p>
                                    <p><strong>Failed Jobs:</strong> {{ \DB::table('failed_jobs')->count() }}</p>
                                    <p><strong>Pending Jobs:</strong> {{ \DB::table('jobs')->count() }}</p>
                                    <hr>
                                    <a href="{{ route('maintenance.queue-status') }}" class="btn btn-info">View Queue Status</a>
                                </div>
                            </div>
                        </div>
                    </div>
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
}
.panel-body {
    padding: 15px;
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
.btn-warning {
    color: #212529;
    background-color: #ffc107;
    border-color: #ffc107;
}
.btn-danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-link {
    font-weight: 400;
    color: #007bff;
    text-decoration: none;
}
.btn-link:hover {
    color: #0056b3;
    text-decoration: underline;
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
.list-unstyled {
    padding-left: 0;
    list-style: none;
}
code {
    padding: 0.2rem 0.4rem;
    font-size: 87.5%;
    color: #e83e8c;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Session management form
    document.getElementById('session-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("maintenance.set-session") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Session updated successfully');
                this.reset();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    });
});
</script>
@endsection
