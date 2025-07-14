<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Gym Services API</title>
</head>

<body>
    <h1>Test Gym Services API</h1>
    <button onclick="testAPI(1)">Test Gym 1 Services</button>
    <button onclick="testAPI(2)">Test Gym 2 Services</button>
    <button onclick="testAPI(3)">Test Gym 3 Services</button>

    <div id="result"></div>

    <script>
        function testAPI(gymId) {
            console.log('Testing API for gym:', gymId);
            const url = `/gym/${gymId}/services`;
            console.log('URL:', url);

            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('API Response:', data);
                    document.getElementById('result').innerHTML =
                        '<h3>Result for Gym ' + gymId + ':</h3>' +
                        '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('result').innerHTML =
                        '<h3>Error for Gym ' + gymId + ':</h3>' +
                        '<p style="color: red;">' + error.message + '</p>';
                });
        }
    </script>
</body>

</html>
