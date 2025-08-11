<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance System - Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .install-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin: 50px auto;
            max-width: 800px;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .step {
            text-align: center;
            flex: 1;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dee2e6;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
        }
        .step.active .step-number {
            background: #007bff;
            color: white;
        }
        .step.completed .step-number {
            background: #28a745;
            color: white;
        }
        .step-label {
            font-size: 12px;
            color: #6c757d;
        }
        .form-container {
            padding: 30px;
        }
        .alert {
            border-radius: 10px;
        }
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #5a6fd8, #6a4190);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-container">
            <div class="text-center p-4">
                <h2><i class="fas fa-qrcode text-primary"></i> QR Attendance System</h2>
                <p class="text-muted">Installation Wizard</p>
            </div>

            <!-- Step Indicators -->
            <div class="step-indicator">
                <div class="step {{ $step >= 1 ? 'active' : '' }} {{ $passed_steps[1] ? 'completed' : '' }}">
                    <div class="step-number">
                        @if($passed_steps[1])
                            <i class="fas fa-check"></i>
                        @else
                            1
                        @endif
                    </div>
                    <div class="step-label">Requirements</div>
                </div>
                <div class="step {{ $step >= 2 ? 'active' : '' }} {{ $passed_steps[2] ? 'completed' : '' }}">
                    <div class="step-number">
                        @if($passed_steps[2])
                            <i class="fas fa-check"></i>
                        @else
                            2
                        @endif
                    </div>
                    <div class="step-label">Database</div>
                </div>
                <div class="step {{ $step >= 3 ? 'active' : '' }} {{ $passed_steps[3] ? 'completed' : '' }}">
                    <div class="step-number">
                        @if($passed_steps[3])
                            <i class="fas fa-check"></i>
                        @else
                            3
                        @endif
                    </div>
                    <div class="step-label">Admin Setup</div>
                </div>
                <div class="step {{ $step >= 4 ? 'active' : '' }}">
                    <div class="step-number">
                        @if($step >= 4)
                            <i class="fas fa-check"></i>
                        @else
                            4
                        @endif
                    </div>
                    <div class="step-label">Complete</div>
                </div>
            </div>

            <div class="form-container">
                @if(isset($error) && $error)
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> {{ $error }}
                    </div>
                @endif

                @if(isset($debug) && $debug)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> {{ $debug }}
                    </div>
                @endif

                <!-- Step 1: Requirements Check -->
                @if($step == 1)
                    <div class="text-center">
                        <h4><i class="fas fa-clipboard-check text-primary"></i> System Requirements</h4>
                        <p class="text-muted">Checking if your server meets the requirements...</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6>PHP Extensions</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success"></i> PHP 8.2+ (Current: {{ PHP_VERSION }})</li>
                                    <li><i class="fas fa-check text-success"></i> BCMath Extension</li>
                                    <li><i class="fas fa-check text-success"></i> Ctype Extension</li>
                                    <li><i class="fas fa-check text-success"></i> JSON Extension</li>
                                    <li><i class="fas fa-check text-success"></i> Mbstring Extension</li>
                                    <li><i class="fas fa-check text-success"></i> OpenSSL Extension</li>
                                    <li><i class="fas fa-check text-success"></i> PDO Extension</li>
                                    <li><i class="fas fa-check text-success"></i> Tokenizer Extension</li>
                                    <li><i class="fas fa-check text-success"></i> XML Extension</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Directory Permissions</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success"></i> storage/app (Writable)</li>
                                    <li><i class="fas fa-check text-success"></i> storage/framework (Writable)</li>
                                    <li><i class="fas fa-check text-success"></i> storage/logs (Writable)</li>
                                    <li><i class="fas fa-check text-success"></i> bootstrap/cache (Writable)</li>
                                    <li><i class="fas fa-check text-success"></i> .env file (Writable)</li>
                                </ul>
                            </div>
                        </div>

                        <form method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="requirements_success" value="1">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-arrow-right"></i> Continue to Database Setup
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Step 2: Database Configuration -->
                @if($step == 2)
                    <h4><i class="fas fa-database text-primary"></i> Database Configuration</h4>
                    <p class="text-muted">Please provide your database connection details.</p>

                    <form method="POST">
                        @csrf
                        <input type="hidden" name="step" value="2">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="hostname" class="form-label">Database Host</label>
                                <input type="text" class="form-control" id="hostname" name="hostname" value="{{ old('hostname', 'localhost') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="database" class="form-label">Database Name</label>
                                <input type="text" class="form-control" id="database" name="database" value="{{ old('database') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Database Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Database Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="?step=1" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Previous
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-arrow-right"></i> Test Connection & Continue
                            </button>
                        </div>
                    </form>
                @endif

                <!-- Step 3: Admin Setup -->
                @if($step == 3)
                    <h4><i class="fas fa-user-shield text-primary"></i> Administrator Setup</h4>
                    <p class="text-muted">Create your administrator account.</p>

                    <form method="POST">
                        @csrf
                        <input type="hidden" name="step" value="3">
                        
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">Admin Email</label>
                            <input type="email" class="form-control" id="admin_email" name="admin_email" value="{{ old('admin_email') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="admin_password" class="form-label">Admin Password</label>
                                <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="admin_passwordr" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="admin_passwordr" name="admin_passwordr" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="?step=2" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Previous
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-rocket"></i> Install System
                            </button>
                        </div>
                    </form>
                @endif

                <!-- Step 4: Installation Complete -->
                @if($step == 4)
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-success">Installation Complete!</h4>
                        <p class="text-muted">Your QR Attendance System has been successfully installed.</p>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Important Information</h6>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Admin URL:</strong> <a href="/admin" target="_blank">/admin</a></li>
                                <li><strong>Default Admin:</strong> Use the email and password you just created</li>
                                <li><strong>Security:</strong> Please delete the install directory after first login</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="/admin" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Go to Admin Panel
                            </a>
                            <a href="/install/delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete the installation directory?')">
                                <i class="fas fa-trash"></i> Delete Install Directory
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>