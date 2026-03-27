<?php

session_start();

require '../config/config.php';
require '../config/common.php';



if(empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])){
  header('Location: login.php');
  exit();
};

if($_SESSION['role'] != 1){
  header('Location: login.php');
};

$searchKey = '';
if(isset($_POST['search'])){
  setcookie('search', $_POST['search'], time() + (86400 * 30), "/"); // 86400 = 1 day
}else{
  if(empty($_GET['pageno'])) {
    unset($_COOKIE['search']);
    setcookie('search','', -1,'/');
  }
};
?>




<?php include('header.php');  ?>



    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Bordered Listing</h3>
              </div>

              <?php

              if(!empty($_GET['pageno'])){
                $pageno = $_GET['pageno'];
              }else{
                $pageno = 1;
              }

              $numOfrecs = 5;
              $offset = ($pageno - 1) * $numOfrecs; //for pages

            
              if(empty($_POST['search']) && empty($_COOKIE['search'])){
                $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                $stmt->execute();
                $rawResult = $stmt->fetchAll();
                $total_pages = ceil(count($rawResult) / $numOfrecs);

                $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs");
                $stmt->execute();
                $result = $stmt->fetchAll();
              }else{
                $searchKey = $_POST['search'] ? $_POST['search'] : $_COOKIE['search'];
                $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
                // print_r($stmt);exit();
                $stmt->execute();
                $rawResult = $stmt->fetchAll();
                $total_pages = ceil(count($rawResult) / $numOfrecs);

                $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs");
                $stmt->execute();
                $result = $stmt->fetchAll();
              }

              ?>
              <!-- /.card-header -->
              <div class="card-body">

                <div>
                  <a href="add.php" type="button" class="btn btn-success">New Blog Post</a>
                </div><br>

                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                  <?php

                  if($result){

                  $i = 1;
                  foreach($result as $value){    ?>

                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo escape($value['title']); ?></td>
                      <td><?php echo escape(substr($value['content'],0,50)); ?></td>
                      <td>
                        <div class="btn-group">
                          <div>
                            <a href="edit.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                          </div>
                          <div>
                            <a href="delete.php?id=<?php echo $value['id'] ?>"
                             onclick="return confirm('Are you sure you want to delete this?')"
                             type="button" class="btn btn-danger">Delete</a>
                          </div>
                        </div>
                      </td>
                    </tr>


                  <?php

                  $i++;
                  }
                  }

                  ?>
                    
                  </tbody>
                </table>

                <nav aria-label="Page navigation example" style="float:right">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                    <li class="page-item" <?php if($pageno <= 1){echo 'disabled';} ?>>
                      <a class="page-link" href="<?php if($pageno <= 1){echo '#';}else{echo "?pageno=".($pageno-1);} ?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                    <li class="page-item" <?php if($pageno >= $total_pages){echo 'disabled';} ?>>
                      <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';}else{echo "?pageno=".($pageno+1);} ?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a></li>
                  </ul>
                </nav>

              </div>
              <!-- /.card-body -->
              
            <!-- /.card -->

            
            <!-- /.card -->
          </div>
          <!-- /.col -->
          
            <!-- /.card -->

            
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include('footer.html'); ?>

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
</body>
</html>
