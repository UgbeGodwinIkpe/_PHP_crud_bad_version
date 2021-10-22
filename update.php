<?php


$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'] ?? null;


if (!$id) {
     header('Location:index.php');
     exit;
}

$statement = $pdo->prepare("SELECT * FROM products WHERE id=:id");
$statement->bindValue(':id', $id);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);


$errors = [];
$title = $product['title'];
$price = $product['price'];
$description = $product['descript'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $title = $_POST['title'];
     $description = $_POST['description'];
     $price = $_POST['price'];
     if (!is_dir('images')) {
          mkdir('images');
     }
     if (empty($errors)) {
          $image = $_FILES['image'] ?? null;
          $imagePath = $product['images'];
          if ($image && $image['tmp_name']) {

               if ($product['images']) {
                    unlink($product['images']);
               }
               $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
               mkdir(dirname($imagePath));
               move_uploaded_file($image['tmp_name'], $imagePath);
          }
          $statement = $pdo->prepare("UPDATE products SET title=:title, descript=:descript, images=:images, price=:price WHERE id=:id");


          $statement->bindValue(':title', $title);
          $statement->bindValue(':descript', $description);
          $statement->bindValue(':images', $imagePath);
          $statement->bindValue(':price', $price);
          $statement->bindValue('id', $id);
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
     <a href="index.php" class="btn btn-secondary">Go Back to Products</a>
     </p>

     <h1>Edit Product <b><?php echo $product['title'] ?></b></h1>
     <?php if (!empty($errors)) : ?>
          <div class="alert alert-danger">
               <?php foreach ($errors as $error) : ?>
                    <di><?php echo $error . '<br>' ?></di>
               <?php endforeach; ?>
          </div>
     <?php endif; ?>

     <form action="" method="POST" enctype="multipart/form-data">
          <?php if ($product['images']) : ?>
               <img src="<?php echo $product['images'] ?>" class="update-image">
          <?php endif; ?>
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
          <button type="submit" class="btn btn-primary">Update</button>
     </form>
</body>

</html>