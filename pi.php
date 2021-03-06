<?php
require_once ('DB.php');

//strip_tags | // check method is get / post
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}
else
{
    echo "Connected to DB with no issue !";
}

$temp_suffix = shell_exec("sudo /opt/vc/bin/vcgencmd measure_temp");
$temp_1 = str_replace("temp=", "", $temp_suffix);
$temp_2 = str_replace("'C", "", $temp_1);

echo "Temp ->           " . $temp_2;
$GetJsonPOST = strip_tags(file_get_contents('php://input'));
$Jobj = json_decode($GetJsonPOST);
$ID = $Jobj->{'ID'};
$Time = $Jobj->{'Time'};
$Temp = $temp_2;

if (($Time != '') && ($Temp != ''))
{
    $sql = "INSERT INTO `$table`(`ID`, `Time`, `Temp`) VALUES (DEFAULT, '$Time', '$Temp')";
    if ($conn->query($sql) === true)
    {
        $output_json = array(
            "Status" => "Success",
            "Reason" => "Information was added with no issues !"
        );
        echo json_encode($output_json);
    }
    else
    {
        $output_json = array(
            "Status" => "Failed",
            "Reason" => "Failed to add information to database !"
        );
        echo json_encode($output_json);
    }
}
else
{
    $output_json = array(
        "Status" => "Failed",
        "Reason" => "Please ensure all information is provided !"
    );
    echo json_encode($output_json);
}

mysqli_close($conn);
?>
