<?php

session_start();

require '../config/config.php';

if(empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])){
  header('Location: login.php');
  exit();
};


if($_POST){                     //name
    $file = 'images/'.($_FILES['image']['name']);
    $imageType = pathinfo($file,PATHINFO_EXTENSION);

    if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg'){
        echo "<script>alert('Image must be png,jpg,jpeg')</script>";
    }else{    
        
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = $_FILES['image']['name'];
                                                        //path
        move_uploaded_file($_FILES['image']['tmp_name'],$file);

        $stmt = $pdo->prepare("INSERT INTO posts(title,content,author_id,image) VALUES (:title,:content,:author_id,:image)");
        $result = $stmt->execute(
            array(':title'=>$title,':content'=>$content,':author_id'=>$_SESSION['user_id'],':image'=>$image)
        );

        if($result){
            echo "<script>alert('Successfully added');window.location.href='index.php';</script>";
            // header('Location: index.php');
        }

    }
}

?>




<?php include('header.php');  ?>



    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
            <form action="add.php" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="">Title</label>
                <input type="text" class="form-control" name="title" value="" required>
              </div>

              <div class="form-group">
                <label for="">Content</label>
                <textarea class="form-control" name="content" id="" rows="8" cols="80"></textarea>
              </div>

              <div class="form-group">
                <label for="">Image</label>
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
