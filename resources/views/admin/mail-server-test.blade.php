<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postfix Mail Server Test - Vitalife Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-seri f;
        }

        .container {
            padding: 20 px 0;
        }

        .test-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin- bottom: 20px;
        }

        .card-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            border-radius: 15px 15px 0 0 !important;

            border: none;
        }

        .btn-test {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            color: white;

            transition: all 0.3s ease;
        }

        .btn-test:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69,
                    0.3);
            color: white;
        }

        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radiu s: 50%;
            mar g in-right: 8px;
        }


        .status-online {
            ba c kground-color: #28a745;
        }


        .status-offline {
            b a ckground-color: #dc3545;
        }


        .status-warning {
            background-color: #ffc107;
        }

        .log-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 12px;

            max-height: 300px;
            overflow-y: auto;
        }

        .config-display {
            background: #e9ecef;
            border-radius: 8px;
            padding: 15px;
            font-famil y: 'Courier New', monospace;
            font-size: 13px;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .spinning {
            animation: s p in 1s linear infinite;


        }

        @k e yframes spin {

            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="test-card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-server me-2"></i>
                            Postfix Mail Server Testing Interface
                        </h4>
                        <small>VPS Mail Server: mail.vitalife.web.id</small>
                    </div>
                    <div class="card-body">

                        <!-- Server Status Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5><i class="fas fa-info-circle me-2"></i>Server Status</h5>
                                <div id="serverStatus" class="config-display">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="status-indicator status-warning"></span>
                                        <span>Loading server status...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fas fa-cogs me-2"></i>SMTP Configuration</h5>
                                <div class="config-display">
                                    <div><strong>Host:</strong> {{ env('MAIL_HOST', 'mail.vitalife.web.id') }}</div>
                                    <div><strong>Port:</strong> {{ env('MAIL_PORT', '587') }}</div>
                                    <div><strong>Encryption:</strong> {{ env('MAIL_ENCRYPTION', 'tls') }}</div>

                                    <div><strong>Username:</strong> {{ env('MAIL_USERNAME', 'admin@vitalife.web.id') }}
                                    </div>

                                    <div><strong>From:</strong> {{ env('MAIL_FROM_ADDRESS', 'admin@vitalife.web.id') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Email Testing Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><i class="fas fa-envelope-open me-2"></i>SMTP Connection Test</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="email" i placeholder="Enter email address for testing"
                                            value="admin@vitalife.web.id">
                                    </div>
                                    <div class="col-md-4">
                                        <button id="testSmtpBtn" class="btn btn-test w-100">
                                            <i class="fas fa-paper-plane me-2"></i>Test SMTP
                                        </button>
                                    </div>
                                </div>
                                <div id="smtpTestResult" class="mt-3"></div>
                            </div>
                        </div>

                        <!-- Service Type Email Testing -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><i class="fas fa-spa me-2"></i>Service Email Templates Test</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="emaiid="serviceTestEmail" class="form-control"
                                            placeholder="Enter email address for service testing"
                                            value="admin@vitalife.web.id">
                                    </div>
                                    <div class="col-md-4">
                                        <select id="serviceType" class="form-select">
                                            <option value="welcome">Welcome Email</option>
                                            <option value="spa">Spa Booking</option>
                                            <option value="yoga">Yoga Booking</option>
                                            <option value="gym">Gym Booking</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button id="testServiceEmailBtn" class="btn btn-test">
                                        <i class="fas fa-envelope me-2"></i>Send Service Email
                                    </button>
                                </div>
                                <div id="serviceEmailResult" class="mt-3"></div>
                            </div>
                        </div>

                        <!-- Queue Status Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5><i class="fas fa-list me-2"></i>Mail Queue Status</h5>
                                <button id="checkQueueBtn" class="btn btn-test mb-3">
                                    <i class="fas fa-refresh me-2"></i>Check Queue
                                </button>
                                <div id="queueStatus" class="config-display">
                                    <div>Click "Check Queue" to view status</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fas fa-file-alt me-2"></i>Recent Logs</h5>
                                <button id="viewLogsBtn" class="btn btn-test mb-3">
                                    <i class="fas fa-eye me-2"></i>View Logs
                                </button>
                                <div id="logDisplay" class="log-container">
                                    <div>Click "View Logs" to display recent mail logs</div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="row">
                            <div class="col-12">
                                <h5><i class="fas fa-tools me-2"></i>Quick Actions</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <button id="refreshStatusBtn" class="btn btn-outline-primary">
                                        <i class="fas fa-sync-alt me-2"></i>Refresh Status
                                    </button>
                                    <button id="testAllBtn" class="btn btn-outline-success">
                                        <i class="fas fa-check-double me-2"></i>Test All Services
                                    </button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Utility function for AJAX calls
        async function makeRequest(url, method = 'GET', data = null) {
                const options = {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                };

                if (data) {
                    options.body = JSON.stringify(data);
                }


                try {
                    const response = await f
                    etch(url, options);
                    return await response.json();
                } catch (error) {
                    console.error('Request failed:', error);
                    return {
                        success: false,
                        message: 'Network erro
                        r: ' + error.message };
                    }
                }

                // Load server status on page load
                async function loadServerStatus() {
                    const statusDiv = document.getElementById('serverStatus');
                    statusDiv.innerHTML =
                        '<div class="d-flex align-items-center"><span class="status-indicator status-warning"></span><span>Loading...</span></div>';

                    const result = await makeRequest('/admin/mail-server/status');

                    if (result.connection === 'success') {
                        statusDiv.innerHTML = `
                    <div class="d-flex align-items-center mb-2">
                        <span class="status-indicator status-online"></span>
                        <span><strong>SMTP Server Online</strong></span>
                    </div>
                    <div><small>${result.message}</small></div>
                    <div><small>Last checked: ${new Date(result.timestamp).toLocaleString()}</small></div>
                `;
                    } else {
                        statusDiv.innerHTML = `
                    <div class="d-flex align-items-center mb-2">
                        <span class="status-indicator status-offline"></span>
                        <span><strong>SMTP Server Offline</strong></span>
                    </div>
                    <div><small class="text-danger">${result.message}</small></div>
                    <div><small>Last checked: ${new Date(result.timestamp).toLocaleString()}</small></div>
                `;
                    }
                }

                // Test SMTP connection
                document.getElementById('testSmtpBtn').addEventListener('click', async function() {
                    const btn = this;
                    const email = document.getElementById('testEmail').value;
                    const resultDiv = document.getElementById('smtpTestResult');

                    if (!email) {
                        resultDiv.innerHTML =
                            '<div class="alert alert-warning">Please enter an email address</div>';


                        return;
                    }

                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner spinning me-2"></i>Testing...';
                    resultDiv.innerHTML = '';

                    const result = await makeRequest('/admin/mail-server/test-smtp', 'POST', {
                        email: email
                    });

                    if (result.success) {
                        resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>${result.message}
                        <small class="d-block mt-2">Server: ${result.details.server}:${result.details.port}</small>
                    </div>
                `;
                    } else {
                        resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>${result.message}
                    </div>
                `;
                    }

                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Test SMTP';
                });

                // Test service emails
                document.getElementById('testServiceEmailBtn').addEventListener('click', async function() {
                    const btn = this;
                    const email = document.getElementById('serviceTestEmail').value;
                    const type = document.getElementById('serviceType').value;
                    const resultDiv = document.getElementById('serviceEmailResult');

                    if (!email) {
                        resultDiv.innerHTML =
                            '<div class="alert alert-warning">Please enter an email address</div>';
                        return;
                    }

                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner spinning me-2"></i>Sending...';
                    resultDiv.innerHTML = '';

                    const result = await makeRequest('/admin/email-test/send', 'POST', {
                        email: email,
                        type: type
                    });

                    if (result.success) {
                        resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>${result.message}
                        <small class="d-block mt-2">SMTP Host: ${result.smtp_host}</small>
                    </div>
                `;
                    } else {
                        resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>${result.message}
                    </div>
                `;
                    }

                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-envelope me-2"></i>Send Service Email';
                });

                // Check queue status
                document.getElementById('checkQueueBtn').addEventListener('click', async function() {
                    const btn = this;
                    const queueDiv = document.getElementById('queueStatus');

                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner spinning me-2"></i>Checking...';

                    const result = await makeRequest('/admin/mail-server/queue-status');

                    if (result.success !== false) {
                        queueDiv.innerHTML = `
                    <div><strong>Queue Status:</strong> ${result.status}</div>
                    <div><strong>Total Messages:</strong> ${result.queue_count}</div>
                    <div><strong>Active:</strong> ${result.active_count}</div>
                    <div><strong>Deferred:</strong> ${result.deferred_count}</div>
                    <div><small>Last checked: ${new Date(result.timestamp).toLocaleString()}</small></div>
                `;
                    } else {
                        queueDiv.innerHTML = `<div class="text-danger">${result.message}</div>`;
                    }

                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-refresh me-2"></i>Check Queue';
                });

                // View logs
                document.getElementById('viewLogsBtn').addEventListener('click', async function() {
                    const btn = this;
                    const logDiv = document.getElementById('logDisplay');

                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner spinning me-2"></i>Loading...';

                    const result = await makeRequest('/admin/mail-server/logs');

                    if (result.success) {
                        let logContent = '<div><strong>Recent Mail Logs:</strong></div>';
                        for (const [logFile, lines] of Object.entries(result.logs)) {
                            logContent += `<div class="mt-2"><small><strong>${logFile}:</strong></small></div>`;
                            lines.slice(-10).forEach(line => {
                                logContent += `<div><small>${line.trim()}</small></div>`;
                            });
                        }
                        logDiv.innerHTML = logContent;
                    } else {
                        logDiv.innerHTML = `<div class="text-danger">${result.message}</div>`;
                    }

                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-eye me-2"></i>View Logs';
                });

                // Refresh status
                document.getElementById('refreshStatusBtn').addEventListener('click', function() {
                    loadServerStatus();
                });

                // Test all services
                document.getElementById('testAllBtn').addEventListener('click', async function() {
                    const btn = this;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner spinning me-2"></i>Testing All...';

                    // Test server status
                    await loadServerStatus();

                    // Test SMTP if email is provided
                    const testEmail = document.getElementById('testEmail').value;
                    if (testEmail) {
                        document.getElementById('testSmtpBtn').click();
                    }

                    // Check queu
                    e
                    document.getElementById('checkQueueBtn').click();

                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-check-double me-2"></i>Test All Services';
                    }, 2000);
                });

                // Initialize page
                document.addEventListener('DOMContentLoaded', function() {
                    loadServerStatus();
                });
    </script>
</body>

</html>
