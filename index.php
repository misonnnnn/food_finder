<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Finders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .recipe-card {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .recipe-card:hover {
            transform: translateY(-5px);
        }

        .btn-primary {
            background-color: #f05454;
            border-color: #f05454;
        }

        .btn-primary:hover {
            background-color: #d43d3d;
            border-color: #d43d3d;
        }

        .card-title {
            color: #333;
        }

        .recipe-img {
            border-radius: 20px 20px 0 0;
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .recipe-content {
            padding: 20px;
        }

        .recipe-description {
            color: #666;
            margin-bottom: 20px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .recipe-description::after {
            content: '...';
            display: inline-block;
        }

        .icon {
            font-size: 24px;
            margin-right: 10px;
        }

        .footer-container {
            border-radius: 0px;
        }
    </style>
</head>
<body>
    <?php
        $search = isset($_GET['search']) ? urlencode($_GET['search']) : null;
        $select = isset($_GET['select']) ? $_GET['select'] : null;
        $data   = [];

        if($search){
            $api_key = 'LK8Mf04tc4RswkzM2BH4Sg==EpndSmjpjFQ73e8c';
            $url = 'https://api.api-ninjas.com/v1/recipe?query='.$search;

            $headers = [
                'http' => [
                    'method' => 'GET',
                    'header' => "X-Api-Key: $api_key\r\n" 
                ]
            ];

            $context = stream_context_create($headers);
            $content = file_get_contents($url, false, $context);
            $data = !empty($content) ? json_decode($content,true) : [];
        }
    ?>
    <div class="container mt-5">
        <div class="form-container mb-4">
            <h2 class="mb-4"><i class="fas fa-utensils icon"></i>Welcome to Food Finders</h2>
            <form action="index.php" method="GET">
                <div class="input-group mb-3">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search food recipe here" value="<?= urldecode($search) ?>">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search icon"></i>Find now!</button>
                </div>
                <p class="text-muted">Think of any food you want to cook!</p>
            </form>
        </div>

        <div class="row">
            <?php 
            if(empty($data)){
                echo "<p class='text-center'>We don't know that yet! Please search other food instead! :(</p>";
            }

            if($select == null){
                foreach($data as $key => $value){
                    ?>
                    <div class="col-md-4 mb-3">
                        <div class="card recipe-card">
                            <div class="recipe-content">
                                <h5 class="card-title"><?= $value['title'] ?></h5>
                                <p class="recipe-description"><?= $value['instructions'] ?></p>
                                <form action="index.php" method="GET">
                                    <input type="hidden" name="search" class="form-control" value="<?= urldecode($search) ?>">
                                    <input type="hidden" name="select" class="form-control" value="<?= $key ?>">
                                    <button type="submit" class="btn btn-dark w-100"><i class="fas fa-utensil-spoon icon"></i>View Recipe</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
            ?>
        </div>
        <div >
            <form action="index.php" method="GET">
                <input type="hidden" name="search" class="form-control" value="<?= urldecode($search) ?>">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-caret-left icon"></i>Back</button>
            </form>
        </div>
        <div class="shadow p-3 mb-5 rounded bg-light fs-6 text">
            <h3><i class="fas fa-utensil-spoon icon"></i><?= $data[$select]['title'] ?></h2>
            <hr>
            <h6>Ingredients <i class="fas fa-list-ul"></i></h6>
            <ul>
                <?php 
                $ingredients = explode('|',$data[$select]['ingredients']);
                foreach($ingredients as $ingredients_l){
                    ?>
                    <li><?= $ingredients_l ?></li>
                    <?php
                }
                ?>
            </ul>
            <hr>
            <h6>Servings <i class="fas fa-th-list"></i></h6>
            <p><?= $data[$select]['servings'] ?></p>
            <hr>
            <h6>Instructions <i class="fas fa-scroll"></i></h6>
            <p><?= $data[$select]['instructions'] ?></p>
        </div>
        <?php
        }
        ?>
    </div>
    </div>

    <div class="container footer-container bg-dark mt-2 text-light p-0">
        <p class="p-2">All right reserve ® 2024 | Developer - John Emmanuel Mison</p>
    </div>
</body>
</html>
