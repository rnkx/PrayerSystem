<?php

session_start();

if(!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])){

    header("Location:user_login.php");
    exit();

}

?>


<!DOCTYPE html>
<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Upload Prayer Content</title>



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<link rel="stylesheet" href="css/style.css">



<style>


body{

background:#f4f7fb;

}



.upload-card{

max-width:650px;

margin:40px auto;

border:none;

border-radius:15px;

box-shadow:0 10px 20px rgba(0,0,0,.1);

}



.upload-header{

background:#4a90e2;

color:white;

padding:25px;

text-align:center;

border-radius:15px 15px 0 0;

}



.form-control,
.form-select{

border-radius:8px;

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


<?php include("navbar.php"); ?>



<div class="container">


<div class="card upload-card">


<div class="upload-header">


<h2>

➕ Upload Prayer Content

</h2>


<p>

Share prayer sutras and melodies with the community.

</p>


</div>




<div class="card-body p-4">



<form action="process_upload.php"
method="POST"
enctype="multipart/form-data"
id="uploadForm">



<label class="form-label">

Content Type

</label>


<select

class="form-select mb-3"

name="type"

id="type"

required>


<option value="sutra">

📖 Prayer Sutra (PDF)

</option>


<option value="melody">

🎵 Prayer Melody (Audio)

</option>


</select>





<label class="form-label">

Title

</label>


<input

type="text"

class="form-control mb-3"

name="title"

required

placeholder="Enter prayer title"

>





<label class="form-label">

Description

</label>


<textarea

class="form-control mb-3"

name="description"

rows="4"

required

placeholder="Enter description">

</textarea>





<label class="form-label">

Cover Image (Optional)

</label>


<input

type="file"

class="form-control mb-3"

name="image"

accept="image/*"

>





<label class="form-label">

Prayer File

</label>


<input

type="file"

class="form-control mb-3"

name="file"

id="file"

required

>





<div class="alert alert-info">


📌 Sutra:

Upload PDF file only.


<br>


🎵 Melody:

Upload MP3/WAV audio file.


</div>





<button

class="btn btn-upload w-100"

type="submit">


➕ Upload Content


</button>



</form>



</div>


</div>


</div>




<?php include("footer.php"); ?>



<script>


const type =
document.getElementById("type");


const file =
document.getElementById("file");



type.addEventListener("change",function(){


if(this.value==="sutra"){


file.accept=".pdf";


}

else{


file.accept="audio/*";


}


});





document.getElementById("uploadForm")
.addEventListener("submit",function(e){



let selected=file.files[0];



if(!selected){

alert("Please select a file.");

e.preventDefault();

return;

}





// Maximum file size 20MB

if(selected.size > 20*1024*1024){


alert("File size must be less than 20MB.");

e.preventDefault();


}



});



</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>