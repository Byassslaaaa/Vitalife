<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Universal Booking System</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .service-selection {
            display: none;
            margin-top: 15px;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h1>Universal Booking System</h1>

    <form id="bookingForm">
        <div class="form-group">
            <label for="booking_type">Booking Type:</label>
            <select id="booking_type" name="booking_type" onchange="handleBookingTypeChange()">
                <option value="">Select Booking Type</option>
                <option value="spa">Spa Booking</option>
                <option value="yoga">Yoga Booking</option>
                <option value="gym">Gym Booking</option>
            </select>
        </div>

        <!-- Common Fields -->
        <div class="form-group">
            <label for="customer_name">Customer Name:</label>
            <input type="text" id="customer_name" name="customer_name" required>
        </div>

        <div class="form-group">
            <label for="customer_email">Customer Email:</label>
            <input type="email" id="customer_email" name="customer_email" required>
        </div>

        <div class="form-group">
            <label for="customer_phone">Customer Phone:</label>
            <input type="text" id="customer_phone" name="customer_phone" required>
        </div>

        <!-- Spa Specific Fields -->
        <div id="spa-fields" class="service-selection">
            <div class="form-group">
                <label for="spa_id">Select Spa:</label>
                <select id="spa_id" name="spa_id">
                    <option value="">Select a Spa</option>
                    <!-- Options will be loaded dynamically -->
                </select>
            </div>

            <div class="form-group">
                <label for="service_id">Select Service:</label>
                <select id="service_id" name="service_id">
                    <option value="">Select a Service</option>
                    <!-- Options will be loaded dynamically -->
                </select>
            </div>

            <div class="form-group">
                <label for="booking_date">Booking Date:</label>
                <input type="date" id="booking_date" name="booking_date">
            </div>

            <div class="form-group">
                <label for="booking_time">Booking Time:</label>
                <input type="time" id="booking_time" name="booking_time">
            </div>

            <div class="form-group">
                <label for="therapist_preference">Therapist Preference:</label>
                <select id="therapist_preference" name="therapist_preference">
                    <option value="">No Preference</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="notes">Special Notes:</label>
                <textarea id="notes" name="notes" rows="3"></textarea>
            </div>
        </div>

        <!-- Yoga Specific Fields -->
        <div id="yoga-fields" class="service-selection">
            <div class="form-group">
                <label for="yoga_id">Select Yoga Class:</label>
                <select id="yoga_id" name="yoga_id">
                    <option value="">Select a Yoga Class</option>
                    <!-- Options will be loaded dynamically -->
                </select>
            </div>

            <div class="form-group">
                <label for="yoga_booking_date">Booking Date:</label>
                <input type="date" id="yoga_booking_date" name="booking_date">
            </div>

            <div class="form-group">
                <label for="yoga_booking_time">Booking Time:</label>
                <input type="time" id="yoga_booking_time" name="booking_time">
            </div>

            <div class="form-group">
                <label for="class_type_booking">Class Type:</label>
                <input type="text" id="class_type_booking" name="class_type_booking"
                    placeholder="e.g., Beginner, Intermediate, Advanced">
            </div>
        </div>

        <!-- Gym Specific Fields -->
        <div id="gym-fields" class="service-selection">
            <div class="form-group">
                <label for="gym_id">Select Gym:</label>
                <select id="gym_id" name="gym_id" onchange="loadGymServices()">
                    <option value="">Select a Gym</option>
                    <!-- Options will be loaded dynamically -->
                </select>
            </div>

            <div class="form-group">
                <label for="gym_service_id">Select Service:</label>
                <select id="gym_service_id" name="service_id">
                    <option value="">Select a Service</option>
                    <!-- Options will be loaded dynamically -->
                </select>
            </div>
        </div>

        <button type="submit">Book Now</button>
    </form>

    <div id="message"></div>

    <script>
        function handleBookingTypeChange() {
            const bookingType = document.getElementById('booking_type').value;

            // Hide all service-specific fields
            document.querySelectorAll('.service-selection').forEach(el => {
                el.style.display = 'none';
            });

            // Show relevant fields
            if (bookingType) {
                document.getElementById(bookingType + '-fields').style.display = 'block';

                // Load data based on booking type
                if (bookingType === 'spa') {
                    loadSpas();
                } else if (bookingType === 'yoga') {
                    loadYogaClasses();
                } else if (bookingType === 'gym') {
                    loadGyms();
                }
            }
        }

        function loadSpas() {
            fetch('/booking/entities?type=spa')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const spaSelect = document.getElementById('spa_id');
                        spaSelect.innerHTML = '<option value="">Select a Spa</option>';
                        data.entities.forEach(spa => {
                            spaSelect.innerHTML +=
                                `<option value="${spa.id}">${spa.name} - ${spa.address}</option>`;
                        });
                    }
                })
                .catch(error => console.error('Error loading spas:', error));
        }

        function loadYogaClasses() {
            fetch('/booking/entities?type=yoga')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const yogaSelect = document.getElementById('yoga_id');
                        yogaSelect.innerHTML = '<option value="">Select a Yoga Class</option>';
                        data.entities.forEach(yoga => {
                            yogaSelect.innerHTML +=
                                `<option value="${yoga.id}">${yoga.name} - ${yoga.location}</option>`;
                        });
                    }
                })
                .catch(error => console.error('Error loading yoga classes:', error));
        }

        function loadGyms() {
            fetch('/booking/entities?type=gym')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const gymSelect = document.getElementById('gym_id');
                        gymSelect.innerHTML = '<option value="">Select a Gym</option>';
                        data.entities.forEach(gym => {
                            gymSelect.innerHTML +=
                                `<option value="${gym.id}">${gym.name} - ${gym.address}</option>`;
                        });
                    }
                })
                .catch(error => console.error('Error loading gyms:', error));
        }

        function loadGymServices() {
            const gymId = document.getElementById('gym_id').value;
            if (gymId) {
                fetch(`/booking/services?type=gym&entity_id=${gymId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const serviceSelect = document.getElementById('gym_service_id');
                            serviceSelect.innerHTML = '<option value="">Select a Service</option>';
                            data.services.forEach(service => {
                                serviceSelect.innerHTML +=
                                    `<option value="${service.id}">${service.name} - Rp ${service.price.toLocaleString()}</option>`;
                            });
                        }
                    })
                    .catch(error => console.error('Error loading gym services:', error));
            }
        }

        document.getElementById('bookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            // Determine the endpoint based on booking type
            let endpoint = '/spa/booking'; // default
            switch (data.booking_type) {
                case 'yoga':
                    endpoint = '/yoga/booking';
                    break;
                case 'gym':
                    endpoint = '/gym/booking';
                    break;
                case 'spa':
                default:
                    endpoint = '/spa/booking';
                    break;
            }

            try {
                const response = await axios.post(endpoint, data, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    }
                });

                if (response.data.success) {
                    document.getElementById('message').innerHTML =
                        '<div class="success">Booking successful! Booking ID: ' + response.data.booking_id +
                        '<br>Payment Token: ' + response.data.payment_token +
                        '<br>Type: ' + response.data.booking_type + '</div>';

                    // Here you would typically redirect to payment page or show payment modal
                    // For Midtrans integration:
                    // window.snap.pay(response.data.payment_token);

                } else {
                    document.getElementById('message').innerHTML =
                        '<div class="error">Booking failed: ' + response.data.message + '</div>';
                }
            } catch (error) {
                document.getElementById('message').innerHTML =
                    '<div class="error">Error: ' + (error.response?.data?.message || error.message) + '</div>';
            }
        });

        // Load services when spa is selected
        document.getElementById('spa_id').addEventListener('change', function() {
            const spaId = this.value;
            if (spaId) {
                loadSpaServices(spaId);
            }
        });

        function loadSpaServices(spaId) {
            fetch(`/booking/services?type=spa&entity_id=${spaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const serviceSelect = document.getElementById('service_id');
                        serviceSelect.innerHTML = '<option value="">Select a Service</option>';
                        data.services.forEach(service => {
                            serviceSelect.innerHTML +=
                                `<option value="${service.id}">${service.name} - Rp ${service.price.toLocaleString()}</option>`;
                        });
                    }
                })
                .catch(error => console.error('Error loading spa services:', error));
        }

        // Load available time slots when date is selected
        function loadAvailableSlots(type, entityId, date) {
            if (type && entityId && date) {
                fetch(`/universal/available-slots?type=${type}&entity_id=${entityId}&date=${date}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update time input to show only available slots
                            console.log('Available slots:', data.available_slots);
                            console.log('Booked slots:', data.booked_slots);
                        }
                    })
                    .catch(error => console.error('Error loading available slots:', error));
            }
        }
    </script>
</body>

</html>
