<?php

session_start();

require '../config/config.php';

if(empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])){
  header('Location: login.php');
  exit();
};

if($_POST){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if($_FILES['image']['name'] != null){

         $file = 'images/'.($_FILES['image']['name']);
        $imageType = pathinfo($file,PATHINFO_EXTENSION);

        if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg'){
            echo "<script>alert('Image must be png,jpg,jpeg')</script>";
        }else{    
            $image = $_FILES['image']['name'];
                                                            //path
            move_uploaded_file($_FILES['image']['tmp_name'],$file);

            $stmt = $pdo->prepare("UPDATE posts SET title='$titel',content='$content',image='$image' WHERE id='$id'");
            $result = $stmt->execute();
            if($result){
                echo "<script>alert('Successfully Update');window.location.href='index.php';</script>";
            }
        }

    }else{
        $stmt = $pdo->prepare("UPDATE posts SET title='$titel',content='$content' WHERE id='$id'");
        $result = $stmt->execute();
        if($result){
            echo "<script>alert('Successfully Update');window.location.href='index.php';</script>";
            // header('Location: index.php');
            }
    }
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
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

                <label for="">Title</label>
                <input type="text" class="form-control" name="title" value="<?php echo $result[0]['title'] ?>" required>
              </div>

              <div class="form-group">
                <label for="">Content</label>
                <textarea class="form-control" name="content" id="" rows="8" cols="80"><?php echo $result[0]['content'] ?></textarea>
              </div>

              <div class="form-group">
                <label for="">Image</label>
                <img src="images/<?php echo $result[0]['image'] ?>" width="150" height="150" alt=""><br><br>
                <input type="file"  name="image" value="" required>
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
