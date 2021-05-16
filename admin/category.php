<?php

  session_start();
  require '../config/config.php';
  require '../config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('location: login.php');
  }


  // pagination
  if(!empty($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
  } else {
    $pageno = 1;
  }

  $numofrecs = 2;
  $offset = ($pageno -1) * $numofrecs;

  if(empty($_POST['search']) && empty($_COOKIE['search'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
    $stmt -> execute();
    $raw_result = $stmt->fetchAll(); 
    $total_pages = ceil(count($raw_result)/$numofrecs);

    $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offset,$numofrecs");
    $stmt -> execute();
    $result = $stmt->fetchAll(); 
  } else {
    $searchKey = (!empty($_POST['search'])) ? $_POST['search'] : $_COOKIE['search'];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%'  ORDER BY id DESC");
    $stmt -> execute();
    $raw_result = $stmt->fetchAll(); 
    $total_pages = ceil(count($raw_result)/$numofrecs);

    $stmt = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '$searchKey%' ORDER BY id DESC LIMIT $offset,$numofrecs");
    $stmt -> execute();
    $result = $stmt->fetchAll(); 
  }
  

?>


<?php include('header.php') ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                  <h3>Category</h3>
              </div>
              <div class="card-body">
                <a href="cat_add.php" type="button" class="btn btn-primary">Create Category</a>
                <table class="table table-bordered table-dark mt-3">
                  <thead>
                    <td>id</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Actions</td>
                  </thead>
                  <tbody>
                    <?php if($result): ?>
                        <?php foreach($result as $value): ?>
                            <tr>
                              <td><?php echo escape($value['id']) ?></td>
                              <td><?php echo escape($value['name']) ?></td>
                              <td><?php echo escape(substr($value['description'],0,50)) ?></td>
                              <td>
                                  <a href="cat_edit.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                                  <a href="cat_delete.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-danger">Delete</a>
                              </td>
                            </tr>
                        <?php endforeach; ?>    
                        <?php else: ?>
                    <?php endif; ?>   
                  </tbody>  
                  
                </table>
                <br>
                <!-- pagination -->
                

              </div>
            </div>
          </div>
  
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  
<?php include('footer.php') ?>