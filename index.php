<?php
// Connecting to the database
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// To search items
$search = $_GET['search'] ?? '';
if ($search) {
     $statement = $pdo->prepare('SELECT * FROM products WHERE title LIKE :title ORDER BY create_date DESC');
     $statement->bindValue(':title', "%$search%");
} else {
     $statement = $pdo->prepare('SELECT * FROM products ORDER BY create_date DESC');
}
// To execute the statement
$statement->execute();
// Fetching the statement and asign to a variable
$products = $statement->fetchAll(PDO::FETCH_ASSOC);



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
     <title>Products CRUD</title>
</head>

<body>
     <h1>Products CRUD</h1>
     <p>
          <a href="create.php" class="btn btn-success">Create Product</a>
     </p>
     <!-- Form to search for products -->
     <form>
          <div class="input-group mb-3">
               <input type="text" class="form-control" placeholder="Search for products" name="search" value="<?php echo $search ?>">
               <button class="btn btn-outline-secondary" type="submit">Search</button>
          </div>
     </form>

     <table class=" table">
          <thead>
               <tr>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Title</th>
                    <th scope="col">Descrption</th>
                    <th scope="col">Price</th>
                    <th scope="col">Create Date</th>
                    <th scope="col">Action</th>
               </tr>
          </thead>
          <tbody>
               <?php foreach ($products as $i => $products) { ?>
                    <tr>
                         <th scope="row"><?php echo $i + 1 ?></th>
                         <td>
                              <img src="<?php echo $products['images'] ?>" class="thumb-image">
                         </td>
                         <td><?php echo $products['title'] ?></td>
                         <td><?php echo $products['descript'] ?></td>
                         <td><?php echo $products['price'] ?></td>
                         <td><?php echo $products['create_date'] ?></td>
                         <td>
                              <a href="update.php?id=<?php echo $products['id'] ?>" type="button" class="btn btn-sm btn-outline-primary">Edit</a>
                              <form action="delete.php" method="POST" style="display:inline-block">
                                   <input type="hidden" name="id" value="<?php echo $products['id'] ?>">
                                   <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                              </form>
                         </td>
                    </tr>
               <?php } ?>

          </tbody>
     </table>
</body>

</html>