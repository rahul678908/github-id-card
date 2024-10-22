<?php
include('config.php');

// Fetch the latest template from the id_card_templates table
$templateSql = "SELECT * FROM id_card_templates ORDER BY created_at DESC LIMIT 1";
$templateResult = $conn->query($templateSql);

// Fetch student data from the students table
$studentSql = "SELECT * FROM students ORDER BY created_at DESC LIMIT 1";
$studentResult = $conn->query($studentSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PreviewCard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        /* Container for ID cards */
        .id-card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        /* General card design */
        .student-card {
            width: 350px;
            height: 450px;
            border: 2px solid #000;
            position: relative;
            background-size: cover;
            background-position: center;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .student-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            position: absolute;
        }

        /* Dynamic field styles */
        .field {
            position: absolute;
        }
        .btn-custom {
            background-color: #C7395F;
            color: #DED4E8;
            font-size: 18px;
            padding: 15px 30px;
            border-radius: 10px;
            transition: background-color 0.3s;
            width: 200px;
            margin: 10px;
        }
        
        .btn-custom:hover {
            background-color: #E8BA40;
            color: #C7395F;
            border-color: #C7395F;
        }
        
        .btn-container {
            text-align: center;
        }
    </style>
</head>
<body>

<h1>Your Identification Card</h1>

<div class="id-card-container">
    <?php
    // Check if a template is available
    if ($templateResult->num_rows > 0) {
        // Fetch the template design data
        $templateRow = $templateResult->fetch_assoc();
        $templateData = json_decode($templateRow['template_data'], true);
        
        // Fetch the background image URL
        $backgroundImage = 'bgimages/' . $templateRow['background_image']; // Assuming it's stored in the 'bgimages' directory

        // Make sure the path is web-accessible and the image exists
        $backgroundImagePath = htmlspecialchars($backgroundImage);

        if (!file_exists($backgroundImagePath)) {
            // Use a fallback image if the image doesn't exist
            $backgroundImagePath = 'bgimages/default_background.png'; // Assuming you have a default image
        }

        // Check if students exist
        if ($studentResult->num_rows > 0) {
            // Output student data based on the template
            while ($studentRow = $studentResult->fetch_assoc()) {
                // Check if a photo exists, otherwise use a default image
                $photo = (!empty($studentRow["photo"])) ? 'uploads/' . $studentRow["photo"] : 'default.jpg';

                // Start rendering the student card with background image
                echo '<div class="student-card" id="student-' . htmlspecialchars($studentRow['roll_number']) . '" style="background-image: url(\'' . htmlspecialchars($backgroundImagePath) . '\');">';

                // Use the template fields to display corresponding student data
                foreach ($templateData as $field) {
                    $fieldName = $field['field'];
                    $fieldPosition = $field['position']; // x and y positions
                    $fontSize = isset($field['style']['fontSize']) ? $field['style']['fontSize'] : '16px'; // Default font size
                    $fontColor = isset($field['style']['color']) ? $field['style']['color'] : '#000'; // Default color is black
                    $fontFamily = isset($field['style']['fontFamily']) ? $field['style']['fontFamily'] : 'Arial, sans-serif'; // Default font-family

                    // Position and style for the fields
                    if (isset($fieldPosition['top']) && isset($fieldPosition['left'])) {
                        $top = $fieldPosition['top'] . 'px';
                        $left = $fieldPosition['left'] . 'px';

                        // Display the student data according to the field
                        switch ($fieldName) {
                            case 'Name':
                                echo '<div class="field" style="top:' . $top . '; left:' . $left . '; font-size:' . $fontSize . '; color:' . $fontColor . '; font-family:' . $fontFamily . ';">' . htmlspecialchars($studentRow['name']) . '</div>';
                                break;
                            case 'Roll Number':
                                echo '<div class="field" style="top:' . $top . '; left:' . $left . '; font-size:' . $fontSize . '; color:' . $fontColor . '; font-family:' . $fontFamily . ';">Roll Number: ' . htmlspecialchars($studentRow['roll_number']) . '</div>';
                                break;
                            case 'Father\'s Name':
                                echo '<div class="field" style="top:' . $top . '; left:' . $left . '; font-size:' . $fontSize . '; color:' . $fontColor . '; font-family:' . $fontFamily . ';">Father: ' . htmlspecialchars($studentRow['father_name']) . '</div>';
                                break;
                            case 'Blood Group':
                                echo '<div class="field" style="top:' . $top . '; left:' . $left . '; font-size:' . $fontSize . '; color:' . $fontColor . '; font-family:' . $fontFamily . ';">Blood Group: ' . htmlspecialchars($studentRow['blood_group']) . '</div>';
                                break;
                            case 'Photo':
                                echo '<img class="field" src="' . htmlspecialchars($photo) . '" style="top:' . $top . '; left:' . $left . '; width: 100px; height: 100px; border-radius: 50%;" alt="Student Photo">';
                                break;
                            case 'Address':
                                echo '<div class="field" style="top:' . $top . '; left:' . $left . '; font-size:' . $fontSize . '; color:' . $fontColor . '; font-family:' . $fontFamily . ';">Address: ' . htmlspecialchars($studentRow['address']) . '</div>';
                                break;
                            case 'Phone Number':
                                echo '<div class="field" style="top:' . $top . '; left:' . $left . '; font-size:' . $fontSize . '; color:' . $fontColor . '; font-family:' . $fontFamily . ';">Phone: ' . htmlspecialchars($studentRow['phone']) . '</div>';
                                break;
                        }
                    }
                }

                // End of the student card
                echo '</div>';
            }
        } else {
            echo "No student data found.";
        }
    } else {
        echo "No template found.";
    }

    // Close the database connection
    $conn->close();
    ?>

</div><br>
<div class="btn-container">
<a href="forms.html" class="btn btn-custom">Back to Forms</a>
</div>
<br><br><br>
<div class="btn-container">
<a href="home.html" class="btn btn-custom">Back to Home</a>
</div>
</body>
</html>
