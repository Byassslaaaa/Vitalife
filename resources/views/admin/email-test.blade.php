<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VPS Email Testing - Vitalife</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 1.1rem;
        }

        .config-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }

        .config-info h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .config-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.9rem;
        }

        .config-label {
            color: #666;
            font-weight: bold;
        }

        .config-value {
            color: #667eea;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 1rem;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .result {
            margin-top: 25px;
            padding: 20px;
            border-radius: 10px;
            font-weight: 600;
            display: none;
        }

        .result.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .result.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .email-types {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .email-type {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #e9ecef;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .email-type:hover {
            border-color: #667eea;
            background: #e8f2ff;
        }

        .email-type.selected {
            border-color: #667eea;
            background: #667eea;
            color: white;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📧 VPS Email Testing</h1>
            <p>Test Email Functionality untuk Vitalife</p>
        </div>

        <div class="config-info">
            <h3>🔧 Current VPS Email Configuration</h3>
            <div class="config-item">
                <span class="config-label">MAIL_HOST:</span>
                <span class="config-value">{{ env('MAIL_HOST', 'mail.vitalife.web.id') }}</span>
            </div>
            <div class="config-item">
                <span class="config-label">MAIL_PORT:</span>
                <span class="config-value">{{ env('MAIL_PORT', '587') }}</span>
            </div>
            <div class="config-item">
                <span class="config-label">MAIL_FROM:</span>
                <span class="config-value">{{ env('MAIL_FROM_ADDRESS', 'admin@vitalife.web.id') }}</span>
            </div>
            <div class="config-item">
                <span class="config-label">MAIL_ENCRYPTION:</span>
                <span class="config-value">{{ env('MAIL_ENCRYPTION', 'tls') }}</span>
            </div>
        </div>

        <form id="emailTestForm">
            @csrf
            <div class="form-group">
                <label for="email">📧 Email Tujuan:</label>
                <input type="email" id="email" name="email" required placeholder="admin@vitalife.web.id"
                    value="admin@vitalife.web.id">
            </div>

            <div class="form-group">
                <label>📋 Pilih Jenis Email:</label>
                <div class="email-types">
                    <div class="email-type" data-type="welcome">
                        🎉<br>Welcome
                    </div>
                    <div class="email-type" data-type="spa">
                        💆‍♀️<br>Spa
                    </div>
                    <div class="email-type" data-type="yoga">
                        🧘‍♀️<br>Yoga
                    </div>
                    <div class="email-type" data-type="gym">
                        💪<br>Gym
                    </div>
                </div>
                <input type="hidden" id="emailType" name="type" required>
            </div>

            <button type="submit" class="btn" id="sendBtn">
                🚀 Kirim Email Test
            </button>
        </form>

        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Mengirim email...</p>
        </div>

        <div class="result" id="result"></div>

        <div class="footer">
            <p>🌟 Vitalife Email System - VPS Production Testing</p>
            <p>Pastikan email berhasil diterima di inbox untuk memverifikasi konfigurasi SMTP</p>
        </div>
    </div>

    <script>
        // CSRF Token setup
        window.Laravel = {
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        // Email type selection
        document.querySelectorAll('.email-type').forEach(type => {
            type.addEventListener('click', function() {
                // Remove selected class from all
                document.querySelectorAll('.email-type').forEach(t => t.classList.remove('selected'));
                // Add to clicked
                this.classList.add('selected');
                // Set hidden input
                document.getElementById('emailType').value = this.dataset.type;
            });
        });

        // Form submission
        document.getElementById('emailTestForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const type = document.getElementById('emailType').value;

            if (!type) {
                alert('Pilih jenis email terlebih dahulu!');
                return;
            }

            // Show loading
            document.getElementById('loading').style.display = 'block';
            document.getElementById('result').style.display = 'none';
            document.getElementById('sendBtn').disabled = true;

            // Send request
            fetch('/email-test/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.Laravel.csrfToken
                    },
                    body: JSON.stringify({
                        email: email,
                        type: type
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loading
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('sendBtn').disabled = false;

                    // Show result
                    const resultDiv = document.getElementById('result');
                    resultDiv.style.display = 'block';
                    resultDiv.className = 'result ' + (data.success ? 'success' : 'error');

                    if (data.success) {
                        resultDiv.innerHTML = `
                        <strong>✅ ${data.message}</strong><br>
                        <small>SMTP Host: ${data.smtp_host || 'N/A'}</small><br>
                        <small>From: ${data.from_address || 'N/A'}</small>
                    `;
                    } else {
                        resultDiv.innerHTML = `<strong>${data.message}</strong>`;
                    }
                })
                .catch(error => {
                    // Hide loading
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('sendBtn').disabled = false;

                    // Show error
                    const resultDiv = document.getElementById('result');
                    resultDiv.style.display = 'block';
                    resultDiv.className = 'result error';
                    resultDiv.innerHTML = `<strong>❌ Error: ${error.message}</strong>`;
                });
        });
    </script>
</body>

</html>
