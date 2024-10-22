<?php
// ob_start(); // Uncomment if needed for output buffering

include('config.php');

// File upload configuration
$targetDir = "../bgimages/"; // Directory to store background images
$allowedTypes = array('jpg', 'jpeg', 'png', 'gif'); // Allowed image types
$maxFileSize = 2 * 1024 * 1024; // 2MB file size limit

// Retrieve template data from POST request
$templateData = isset($_POST['front_template']) ? $_POST['front_template'] : '';

// Ensure the template data is not empty
if (!empty($templateData)) {

    // Decode the JSON data from the POST request
    $templateData = json_decode($templateData, true);
    
    // Check if the background image exists in the template data
    $backgroundImage = isset($templateData[count($templateData) - 1]['front_design']) ? $templateData[count($templateData) - 1]['front_design'] : null;

    // Prepare the template data for storage (excluding the background image)
    $fieldsData = json_encode(array_filter($templateData, function ($field) {
        return isset($field['field']);  // Filter out the background image data
    }));

    $savedBackgroundImage = '';

    // If a background image exists, handle the image saving process
    if ($backgroundImage) {
        // Decode the base64 image data
        $imageParts = explode(";base64,", $backgroundImage);
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = strtolower($imageTypeAux[1]);

        if (in_array($imageType, $allowedTypes)) {
            $imageBase64 = base64_decode($imageParts[1]);
            $fileName = uniqid() . '.' . $imageType; // Generate unique filename
            $filePath = $targetDir . $fileName;

            // Ensure the directory exists and has proper permissions
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            // Save the image to the local directory
            if (file_put_contents($filePath, $imageBase64)) {
                // Save the file path of the image to the database
                $savedBackgroundImage = $fileName;
            } else {
                die("Error: Could not save the background image.");
            }
        } else {
            die("Error: Invalid image type.");
        }
    }

    // SQL to insert template data and background image into the database
    $sql = "INSERT INTO idcard_template (front_template, front_design, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    // Bind the template data and background image path to the query
    $stmt->bind_param("ss", $fieldsData, $savedBackgroundImage);

    // Execute the SQL statement
    if ($stmt->execute()) {
        // Redirect to the next page after successful save
        // echo "<script type='text/javascript'>
        //           alert('Template and background image saved successfully!');
        //           window.location.href = 'idbackside.php'; // Correct redirection to 'idbackside.php'
        //       </script>";
        ?>
            <script type='text/javascript'>
                alert('Template and background image saved successfully!');
                window.location.href = 'idbackside.php'; // Correct redirection to 'idbackside.php'
            </script>
       <?php  
        
        } else {
        // Output error message
        echo "Error: " . $stmt->error;
    }
    

    $stmt->close();
} else {
    echo "Error: Template data is missing.";
}

$conn->close();
// ob_end_flush(); // Uncomment if output buffering is needed

?>


