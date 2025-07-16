<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brevo Email Testing - Vitalife Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">📧 Brevo Email Testing Dashboard</h1>
                <p class="text-gray-600">Test email functionality dengan Brevo SMTP untuk aplikasi Vitalife</p>
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <strong>📌 Info:</strong> Testing email registration, booking confirmation, dan payment success
                        notification
                    </p>
                </div>
            </div>

            <!-- Email Configuration Status -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">⚙️ Email Configuration</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700">SMTP Host</h3>
                        <p class="text-gray-600">{{ env('MAIL_HOST') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700">SMTP Port</h3>
                        <p class="text-gray-600">{{ env('MAIL_PORT') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700">Encryption</h3>
                        <p class="text-gray-600">{{ env('MAIL_ENCRYPTION') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700">From Address</h3>
                        <p class="text-gray-600">{{ env('MAIL_FROM_ADDRESS') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Test Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">🚀 Quick Email Tests</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Welcome Email Test -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-700 mb-2">📨 Welcome Email</h3>
                        <p class="text-sm text-gray-600 mb-3">Test email registrasi user baru</p>
                        <input type="email" id="quickWelcomeEmail"
                            class="w-full border border-gray-300 rounded px-3 py-2 mb-3 text-sm"
                            placeholder="test@gmail.com"
                            value="{{ auth()->user()->email ?? env('MAIL_FROM_ADDRESS') }}">
                        <button onclick="sendQuickWelcome()"
                            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                            Send Welcome
                        </button>
                    </div>

                    <!-- Spa Booking Test -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-700 mb-2">🌺 Spa Booking</h3>
                        <p class="text-sm text-gray-600 mb-3">Test konfirmasi booking spa</p>
                        <input type="email" id="quickSpaEmail"
                            class="w-full border border-gray-300 rounded px-3 py-2 mb-3 text-sm"
                            placeholder="test@gmail.com"
                            value="{{ auth()->user()->email ?? env('MAIL_FROM_ADDRESS') }}">
                        <button onclick="sendQuickBooking('spa')"
                            class="w-full bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded text-sm">
                            Send Spa Email
                        </button>
                    </div>

                    <!-- Yoga Booking Test -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-700 mb-2">🧘‍♀️ Yoga Booking</h3>
                        <p class="text-sm text-gray-600 mb-3">Test konfirmasi booking yoga</p>
                        <input type="email" id="quickYogaEmail"
                            class="w-full border border-gray-300 rounded px-3 py-2 mb-3 text-sm"
                            placeholder="test@gmail.com"
                            value="{{ auth()->user()->email ?? env('MAIL_FROM_ADDRESS') }}">
                        <button onclick="sendQuickBooking('yoga')"
                            class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded text-sm">
                            Send Yoga Email
                        </button>
                    </div>
                </div>
            </div>

            <!-- Test Results -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">📊 Test Results</h2>
                <div id="testResults" class="space-y-2">
                    <p class="text-gray-500">No tests run yet. Click buttons above to test email functionality.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function sendQuickWelcome() {
            const email = $('#quickWelcomeEmail').val();
            if (!email) {
                addTestResult('error', '❌ Please enter a valid email address');
                return;
            }

            addTestResult('info', `📧 Sending welcome email to ${email}...`);

            // Use existing email test route
            $.post('/admin/email-test/send', {
                    type: 'welcome',
                    email: email
                })
                .done(function(response) {
                    if (response.success) {
                        addTestResult('success', `✅ Welcome email sent to ${email}`);
                    } else {
                        addTestResult('error', `❌ Failed: ${response.message}`);
                    }
                })
                .fail(function(xhr) {
                    const error = xhr.responseJSON ? xhr.responseJSON.message : 'Request failed';
                    addTestResult('error', `❌ Welcome email failed: ${error}`);
                });
        }

        function sendQuickBooking(type) {
            const emailField = type === 'spa' ? '#quickSpaEmail' : '#quickYogaEmail';
            const email = $(emailField).val();

            if (!email) {
                addTestResult('error', '❌ Please enter a valid email address');
                return;
            }

            addTestResult('info', `📧 Sending ${type} booking email to ${email}...`);

            // Use existing email test route
            $.post('/admin/email-test/send', {
                    type: type,
                    email: email
                })
                .done(function(response) {
                    if (response.success) {
                        addTestResult('success',
                            `✅ ${type.charAt(0).toUpperCase() + type.slice(1)} booking email sent to ${email}`);
                    } else {
                        addTestResult('error', `❌ Failed: ${response.message}`);
                    }
                })
                .fail(function(xhr) {
                    const error = xhr.responseJSON ? xhr.responseJSON.message : 'Request failed';
                    addTestResult('error', `❌ ${type} booking email failed: ${error}`);
                });
        }

        function addTestResult(type, message) {
            const timestamp = new Date().toLocaleTimeString();
            const typeClass = {
                'info': 'text-blue-600 bg-blue-50 border-blue-200',
                'success': 'text-green-600 bg-green-50 border-green-200',
                'error': 'text-red-600 bg-red-50 border-red-200'
            } [type] || 'text-gray-600 bg-gray-50 border-gray-200';

            const resultHtml = `
                <div class="p-3 rounded-lg border ${typeClass}">
                    <div class="flex justify-between items-start">
                        <span>${message}</span>
                        <span class="text-xs opacity-75">${timestamp}</span>
                    </div>
                </div>
            `;

            const $results = $('#testResults');
            if ($results.find('p.text-gray-500').length) {
                $results.empty();
            }
            $results.prepend(resultHtml);

            // Keep only last 10 results
            const $resultItems = $results.children();
            if ($resultItems.length > 10) {
                $resultItems.slice(10).remove();
            }
        }
    </script>
</body>

</html>
