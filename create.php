<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
$title = '';
$price = '';
$description = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $title = $_POST['title'];
     $description = $_POST['description'];
     $price = $_POST['price'];
     $date = Date('Y-m-d H:i:s');
     if (!is_dir('images')) {
          mkdir('images');
     }
     if (empty($errors)) {
          $image = $_FILES['image'] ?? null;
          $imagePath = '';
          if ($image && $image['tmp_name']) {

               $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
               mkdir(dirname($imagePath));
               move_uploaded_file($image['tmp_name'], $imagePath);
          }
          $statement = $pdo->prepare("INSERT INTO products (title, descript, images, price, create_date)
             VALUES(:title, :descript, :images, :price, :create_date)
          ");
          $statement->bindValue(':title', $title);
          $statement->bindValue(':descript', $description);
          $statement->bindValue(':images', $imagePath);
          $statement->bindValue(':price', $price);
          $statement->bindValue(':create_date', $date);
          $statement->execute();
          header('Location: index.php');
     }
     if (!$title) {
          $errors[] = 'Product title is required';
     }
     if (!$price) {
          $errors[] = 'Product price is required';
     }
}

function randomString($n)
{
     $characters = '0123456789abcdefghijklmnoABCDEFGHIJKLMNO';
     $str = '';
     for ($i = 0; $i < $n; $i++) {
          $index = rand(0, strlen($characters) - 1);
          $str .= $characters[$index];
     }

     return $str;
}

?>
<!doctype html>
<html lang="en">

<head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">

     <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
     <link rel="stylesheet" href="app.css">
     <title>Create new Product</title>
</head>

<body>
     <h1>Create new Product</h1>
     <?php if (!empty($errors)) : ?>
          <div class="alert alert-danger">
               <?php foreach ($errors as $error) : ?>
                    <di><?php echo $error . '<br>' ?></di>
               <?php endforeach; ?>
          </div>
     <?php endif; ?>

     <form action="" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
               <label for="image" class="form-label">Product Image</label><br>
               <input type="file" name="image">
          </div>
          <div class="mb-3">
               <label for="title" class="form-label">Product title *</label>
               <input type="text" class="form-control" name="title" value="<?php echo $title ?>">
          </div>
          <div class="mb-3">
               <label for="description" class="form-label">Product Description</label>
               <textarea class="form-control" name="description"><?php echo $description ?></textarea>
          </div>
          <div class="mb-3">
               <label for="price" class="form-label">Product Price *</label>
               <input type="number" step='.01' class="form-control" name="price" value="<?php echo $price ?>">
          </div>
          <!-- <div class="mb-3">
               <label for="date" class="form-label" name="date">Create date</label>
               <input type="date" class="form-control">
          </div> -->
          <button type="submit" class="btn btn-primary">Create</button>
     </form>
</body>

</html>