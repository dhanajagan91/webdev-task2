<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>trying out </title>
     
    <style>
    body{
        font-family:arial;
        font-size:.8em;
    }
     
    input[type=text]{
        padding:0.5em;
        width:20em;
    }
     
    input[type=submit]{
        padding:0.4em;
    }
     
    #gmap_canvas{
        width:100%;
        height:30em;
    }
     
    #map-label,
    #address-examples{
        margin:1em 0;
    }
    </style>
 
</head>
<body>

 <!-- enter any address -->
<form action="" method="post">
    <input type='text' name='latitude' placeholder='latitude(format=aa.cccc)' />
 <input type='text' name='longitude' placeholder='longitude(format=aa.cccc)' />
 <input type='text' name='zoom' placeholder='initial zoom(format=bbbb)' />
    <input type='submit' value='get the map' />
</form>
<br>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myDB";
$latitude=$_POST['latitude'];
$longitude=$_POST['longitude'];
 $zoom=$_POST['zoom'];
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}        
    $sql="select * from Mytable2 WHERE latitude=$latitude
          AND longitude=$longitude AND zoom=$zoom";
    $result = mysqli_query($conn,$sql);
if (mysqli_num_rows($result) == 0)
       {
         $sql2 = "INSERT INTO Mytable2 (latitude, longitude, zoom)
             VALUES ('$latitude','$longitude','$zoom')";
        
            if ($conn->query($sql2) === TRUE) 
                 {
              echo "New record created successfully";
                }
            else {
                   echo "Error: " . $sql . "<br>" . $conn->error;
                  }
         }
else
     { $sql3="SET @pos=(select TIMEDIFF(NOW(),E.reg_date) FROM 'mytable2' E
                        WHERE E.latitude='" . mysqli_escape_string($conn,$latitude) . "'
                        AND E.longitude='" . mysqli_escape_string($conn,$longitude) . "'
                        AND E.zoom='" . mysqli_escape_string($conn,$zoom) . "');
              SET @DIFF=EXTRACT(HOUR FROM @pos);
              SELECT @DIFF";
       $result2 = mysqli_query($conn,$sql3);
       $timediff = mysqli_fetch_assoc($result2);
       
           if($timediff>1)
         {   $sql2="UPDATE Mytable2 
                   SET reg_date=NOW();
                    WHERE latitude=$latitude
                   AND longitude=$longitude AND zoom=$zoom";
               if ($conn->query($sql2) === TRUE) 
                 {
              echo " record updated successfully";
                }
            else {
                   echo "Error: " . $sql . "<br>" . $conn->error;
                  }
         }
         else
          echo "enter new record now";
     }
           
$conn->close();
?>
<div id="gmap_canvas">Loading map...</div>
<!-- JavaScript to show google map -->
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>    
    <script type="text/javascript">
        function init_map() {
            var myOptions = {
                zoom: <?php echo $zoom; ?>,
                center: new google.maps.LatLng( <?php echo $latitude; ?>,<?php echo $longitude; ?>),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
           }
        google.maps.event.addDomListener(window, 'load', init_map);
    </script>


</body>
</html>
