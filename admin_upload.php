<?php

session_start();


// Only admin can access

if(!isset($_SESSION['admin_id'])){

    header("Location:admin_login.php");
    exit();

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Admin Upload | Prayer System</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<link rel="stylesheet" href="css/style.css">


<style>


body{

    background:#f4f7fb;

}



.upload-box{

    max-width:700px;

    margin:50px auto;

}



.card{

    border-radius:15px;

    border:none;

    box-shadow:0 8px 20px rgba(0,0,0,0.1);

}



.card-header{

    background:#2c3e50;

    color:white;

    text-align:center;

    padding:25px;

    border-radius:15px 15px 0 0;

}



.card-header h2{

    color:white;

}



.btn-upload{

    background:#4a90e2;

    color:white;

    font-weight:bold;

}



.btn-upload:hover{

    background:#357ab8;

    color:white;

}


</style>


</head>



<body>



<?php include('admin_navbar.php'); ?>





<div class="container upload-box">


<div class="card">



<div class="card-header">


<h2>

📤 Upload Prayer Content

</h2>


<p class="mb-0">

Add prayer sutras and prayer melodies

</p>


</div>





<div class="card-body p-4">





<form action="process_admin_upload.php"
method="POST"
enctype="multipart/form-data">





<!-- Type -->


<label class="form-label fw-bold">

Content Type

</label>


<select 
name="type"
id="type"
class="form-select mb-3"
required>


<option value="sutra">

📖 Prayer Sutra (PDF)

</option>


<option value="melody">

🎵 Prayer Melody (Audio)

</option>


</select>







<!-- Title -->


<label class="form-label fw-bold">

Title

</label>


<input type="text"
name="title"
class="form-control mb-3"
placeholder="Enter prayer title"
required>







<!-- Description -->


<label class="form-label fw-bold">

Description

</label>


<textarea
name="description"
class="form-control mb-3"
rows="4"
placeholder="Enter description"
required></textarea>







<!-- Image -->


<label class="form-label fw-bold">

Cover Image

</label>


<input 
type="file"
name="image"
class="form-control mb-3"
accept="image/*">







<!-- File -->


<label class="form-label fw-bold">

Prayer File

</label>


<input 
type="file"
name="file"
id="file"
class="form-control mb-3"
required>





<div class="alert alert-info">


<strong>File Requirement:</strong>


<ul class="mb-0">

<li>
Sutra → PDF format
</li>


<li>
Melody → MP3 / WAV format
</li>


</ul>


</div>







<button type="submit"
class="btn btn-upload w-100">


➕ Upload Content


</button>





</form>




</div>


</div>


</div>






<?php include('footer.php'); ?>






<script>


const typeSelect =
document.getElementById("type");


const fileInput =
document.getElementById("file");



typeSelect.addEventListener(
"change",
function(){


if(this.value==="sutra"){


    fileInput.accept=".pdf";


}

else{


    fileInput.accept="audio/*";


}


});



</script>






<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



</body>

</html>