<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charts</title>
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <nav>
            <ul class='nav-bar'>
            <li><a href="graph.php" onclick="event.stopPropagation();">Chart</a></li>
                <li><a href="prediction.php" id="predictionLink">Prediction</a></li>
                <label for="check" class="close-menu"><i class="fas fa-times"></i></label>
            </ul>
        </nav>
    </header>
    <script>
        document.getElementById('predictionLink').addEventListener('click', function(event) {
            event.stopPropagation();
        });
    </script>
</body>
</html>
