<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            text-align: center;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Buy Access</h1>
        <p>Select your plan:</p>
        <form action="process_payment.php" method="POST">
            <input type="radio" name="plan" value="10" id="1hour" required>
            <label for="1hour">$10 = 1 Hour</label><br>
            <input type="radio" name="plan" value="25" id="24hours">
            <label for="24hours">$25 = 24 Hours</label><br>
            <input type="radio" name="plan" value="135" id="1week">
            <label for="1week">$135 = 1 Week</label><br>
            <input type="radio" name="plan" value="400" id="1month">
            <label for="1month">$400 = 1 Month</label><br><br>
            <label for="phone">Enter your phone number:</label><br>
            <input type="text" id="phone" name="phone" required><br><br>
            <input type="submit" class="button" value="Proceed to Payment">
        </form>
    </div>
</body>
</html>
