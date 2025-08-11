<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance System - Requirements Check</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .requirements-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin: 50px auto;
            max-width: 800px;
        }
        .requirement-item {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            border-left: 4px solid;
        }
        .requirement-success {
            background: #d4edda;
            border-left-color: #28a745;
        }
        .requirement-error {
            background: #f8d7da;
            border-left-color: #dc3545;
        }
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="requirements-container">
            <div class="text-center p-4">
                <h2><i class="fas fa-qrcode text-primary"></i> QR Attendance System</h2>
                <p class="text-muted">System Requirements Check</p>
            </div>

            <div class="p-4">
                <h4><i class="fas fa-clipboard-check text-primary"></i> PHP Requirements</h4>
                
                @foreach($requirements as $requirement => $met)
                    <div class="requirement-item {{ $met ? 'requirement-success' : 'requirement-error' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">{{ $requirement }}</span>
                            <span>
                                @if($met)
                                    <i class="fas fa-check-circle text-success"></i> Passed
                                @else
                                    <i class="fas fa-times-circle text-danger"></i> Failed
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach

                <h4 class="mt-4"><i class="fas fa-folder-open text-primary"></i> Directory Permissions</h4>
                
                @foreach($permissions as $path => $writable)
                    <div class="requirement-item {{ $writable ? 'requirement-success' : 'requirement-error' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">{{ $path }}</span>
                            <span>
                                @if($writable)
                                    <i class="fas fa-check-circle text-success"></i> Writable
                                @else
                                    <i class="fas fa-times-circle text-danger"></i> Not Writable
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach

                @php
                    $allRequirementsMet = collect($requirements)->every(fn($met) => $met);
                    $allPermissionsWritable = collect($permissions)->every(fn($writable) => $writable);
                    $canProceed = $allRequirementsMet && $allPermissionsWritable;
                @endphp

                <div class="mt-4 text-center">
                    @if($canProceed)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> All requirements are met! You can proceed with the installation.
                        </div>
                        <a href="/install" class="btn btn-primary">
                            <i class="fas fa-arrow-right"></i> Continue Installation
                        </a>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> Some requirements are not met. Please fix the issues above before proceeding.
                        </div>
                        <button class="btn btn-secondary" onclick="location.reload()">
                            <i class="fas fa-refresh"></i> Recheck Requirements
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>