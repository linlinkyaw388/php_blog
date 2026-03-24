<?php

session_start();

require '../config/config.php';

if(empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])){
  header('Location: login.php');
  exit();
};

if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    if (empty($_POST['role'])){
        $role = 0;
    }else{
        $role = 1;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
    $stmt->execute(array(':email'=>$email,':id'=>$id));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if($user){
        echo "<script>alert('Email duplicated')</script>";
    }else{
        $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id='$id'");
        $result = $stmt->execute();

        if($result){
            echo "<script>alert('Successfully update.');window.location.href='user_list.php';</script>";
            // header('Location: index.php');
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

?>

<?php include('header.php');  ?>



    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
              <div class="form-group">
                
                <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">

                <label for="">Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo $result[0]['name'] ?>" required>
              </div>

              <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $result[0]['email'] ?>">
              </div>

              <div class="form-group">
                <label for="vehicle3">Admin</label>
                <input type="checkbox" name="role" value="<?php echo $value['role'];?>">
              </div>

              <div class="form-group">
                <input type="submit" class="btn btn-success" name="" value="Submit">
                <a href="index.php" class="btn btn-warning">Back</a>
              </div>
              </div>
            <!-- /.card -->
            </div>
            </form>

            
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
