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
}

if($_POST){

  if(empty($_POST['title']) || empty($_POST['content'])){

      if(empty($_POST['title'])){
        $titleError = "Title cannot be null";
      }
      if(empty($_POST['content'])){
        $contentError = "content cannot be null";
      }
      
    }else{

      $id = $_POST['id'];
      $title = $_POST['title'];
      $content = $_POST['content'];
      $image = $_FILES['image'];

      if($_FILES['image']['name'] != null){

          $file = 'images/'.($_FILES['image']['name']);
          $imageType = pathinfo($file,PATHINFO_EXTENSION);

          if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg'){
              echo "<script>alert('Image must be png,jpg,jpeg')</script>";
          }else{    
              $image = $_FILES['image']['name'];
                                                              //path
              move_uploaded_file($_FILES['image']['tmp_name'],$file);

              $stmt = $pdo->prepare("UPDATE posts SET title=:title, content=:content, image=:image WHERE id=:id");
              $result = $stmt->execute(
                [
                ':title' => $title,
                ':content' => $content,
                ':image' => $image,
                ':id' => $id
                ]
              );
              if($result){
                  echo "<script>alert('Successfully Update');window.location.href='index.php';</script>";
              }
          }

      }else{
          $stmt = $pdo->prepare("UPDATE posts SET title=:title, content=:content WHERE id=:id");
          $result = $stmt->execute(
            [
            ':title' => $title,
            ':content' => $content,
            ':id' => $id
            ]
          );
          if($result){
              echo "<script>alert('Successfully Update');window.location.href='index.php';</script>";
              // header('Location: index.php');
              }
      }

    }
    
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
$stmt->bindValue(':id',$_GET['id']);
$stmt->execute();
// $result = $stmt->fetch();
$result = $stmt->fetch(PDO::FETCH_ASSOC); // fetchAll အစား fetch ပဲ သုံးလိုက်ပါ (ID က တစ်ခုပဲမို့လို့)

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
              <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'];?>">
              <div class="form-group">
                
                <input type="hidden" name="id" value="<?php echo $result['id'] ?>">

                <label for="">Title</label><p style="color: red;"><?php echo empty($titleError) ? '' : '*'.$titleError; ?></p>
                <input type="text" class="form-control" name="title" value="<?php echo escape($result['title']) ?>">
              </div>

              <div class="form-group">
                <label for="">Content</label><p style="color: red;"><?php echo empty($contentError) ? '' : '*'.$contentError; ?></p>
                <textarea class="form-control" name="content" id="" rows="8" cols="80"><?php echo escape($result['content']) ?></textarea>
              </div>

              <div class="form-group">
                <label for="">Image</label><br>
                <img src="images/<?php echo $result['image'] ?>" width="150" height="150" alt=""><br><br>
                <input type="file"  name="image" value="">
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
