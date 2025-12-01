<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Sports Shop'; ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link 
        rel="stylesheet" 
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" 
        integrity="sha384-xO-5f8k4P3lY6l3R/Q0p5wP9xR2T5/M5o2T5P2G5Wp2X5F9f8N5I/T2U3Wp2R" 
        crossorigin="anonymous"
    >

    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

    <div class="main-content">
        <?php echo $content; ?>
    </div>

    </body>
</html>