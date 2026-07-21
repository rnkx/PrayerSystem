<?php

session_start();

include('database/db.php');


// ===============================
// Admin Access Only
// ===============================

if(!isset($_SESSION['admin_id'])){

    header("Location:admin_login.php");
    exit;

}


$admin_id = $_SESSION['admin_id'];



if(isset($_POST['type'])){


$type = $_POST['type'];

$title = trim($_POST['title']);

$description = trim($_POST['description']);




// ===============================
// Upload folders
// ===============================


$imageFolder = "uploads/image/";
$pdfFolder   = "uploads/pdf/";
$audioFolder = "uploads/audio/";



foreach([
    $imageFolder,
    $pdfFolder,
    $audioFolder
] as $folder){

    if(!is_dir($folder)){

        mkdir($folder,0777,true);

    }

}





// ===============================
// Upload Image
// ===============================


$imageName = "default.jpg";



if(isset($_FILES['image']) &&
!empty($_FILES['image']['name'])){


    $imageExt = strtolower(
        pathinfo(
            $_FILES['image']['name'],
            PATHINFO_EXTENSION
        )
    );



    $allowedImages = [
        "jpg",
        "jpeg",
        "png",
        "gif"
    ];



    if(!in_array($imageExt,$allowedImages)){


        die("Invalid image format");


    }



    $imageName =
    time()."_image.".$imageExt;



    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $imageFolder.$imageName
    );


}







// ===============================
// Upload Sutra
// ===============================


if($type=="sutra"){



    $fileExt = strtolower(
        pathinfo(
            $_FILES['file']['name'],
            PATHINFO_EXTENSION
        )
    );



    if($fileExt!="pdf"){


        die("Sutra file must be PDF");


    }



    $fileName =
    time()."_sutra.pdf";



    move_uploaded_file(

        $_FILES['file']['tmp_name'],

        $pdfFolder.$fileName

    );





    $sql=$conn->prepare(

        "INSERT INTO sutras
        (title, description, image, file, uploaded_by)
        VALUES (?,?,?,?,?)"

    );



    if(!$sql){

        die("SQL Error: ".$conn->error);

    }



    $sql->bind_param(

        "ssssi",

        $title,

        $description,

        $imageName,

        $fileName,

        $admin_id

    );





    if($sql->execute()){


        echo "

        <script>

        alert('Prayer Sutra uploaded successfully');

        window.location='manage_sutras.php';

        </script>

        ";


    }
    else{


        die($sql->error);


    }



}







// ===============================
// Upload Melody
// ===============================


elseif($type=="melody"){



    $fileExt = strtolower(
        pathinfo(
            $_FILES['file']['name'],
            PATHINFO_EXTENSION
        )
    );



    $allowedAudio=[

        "mp3",
        "wav",
        "ogg"

    ];



    if(!in_array($fileExt,$allowedAudio)){


        die("Invalid audio format");


    }



    $fileName =
    time()."_melody.".$fileExt;




    move_uploaded_file(

        $_FILES['file']['tmp_name'],

        $audioFolder.$fileName

    );





    $sql=$conn->prepare(

        "INSERT INTO melodies
        (title, description, image, file)
        VALUES (?,?,?,?)"

    );





    if(!$sql){

        die("SQL Error: ".$conn->error);

    }





    // FIXED: 4 variables = ssss

    $sql->bind_param(

        "ssss",

        $title,

        $description,

        $imageName,

        $fileName

    );






    if($sql->execute()){


        echo "

        <script>

        alert('Prayer Melody uploaded successfully');

        window.location='manage_melodies.php';

        </script>

        ";


    }
    else{


        die($sql->error);


    }



}




else{


    echo "Invalid content type";


}



}

else{


header("Location:admin_upload.php");

exit;


}


?>