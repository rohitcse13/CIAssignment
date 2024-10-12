<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Information</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Weather Information</h1>

        <?php if (isset($weather)): ?>
            <div class="alert alert-success">
                <h2><?php echo $weather['city']; ?></h2>
                <p>Temperature: <?php echo $weather['temperature']; ?> °C</p>
                <p>Description: <?php echo $weather['description']; ?></p>
                <p>Humidity: <?php echo $weather['humidity']; ?>%</p>
                <p>Wind Speed: <?php echo $weather['wind_speed']; ?> m/s</p>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
