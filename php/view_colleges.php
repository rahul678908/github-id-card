<!DOCTYPE html>
<html lang="en">
<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- site metas -->
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- responsive -->
    <link rel="stylesheet" href="../css/responsive.css">
    <!-- awesome fontfamily -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        /* Styling for the action buttons */
        .action-button {
            margin-right: 10px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }

        .edit-button {
            background-color: black; 
        }

        .save-button {
            background-color: #008CBA; 
        }

        .table td {
            vertical-align: middle;
        }
        
        .table, th, td {
            border: 2px solid black;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
        }
        
    </style>
</head>
<body class="main-layout inner_page">
<div id="mySidepanel" class="sidepanel">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <a href="index.html">Home</a>
        <a class="active" href="colleges.html">Create College</a>
        <a href="classwise.html">Class Wise</a>
        <a href="studentwise.html">Student Wise</a>
        <a href="template.html">Templates</a>
     </div>

    <!-- header -->
    <header>
        <div class="head-top">
            <div class="container-fluid">
                <div class="row d_flex align-items-center justify-content-between">
                    <div class="col-auto">
                        <button class="openbtn" onclick="openNav()">
                            <img src="../images/menu_btn.png" />
                        </button>
                    </div>
                    <div class="col-auto">
                        <ul class="email d-flex justify-content-end align-items-center m-0 p-0">
                            <li class="me-3">
                                <a href="javascript:void(0)">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- end header -->

    <div class="about">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage text_align_center">
                        <h2>Colleges List</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <?php
                        include('config.php');

                        // Retrieve data from the database
                        $query = "SELECT id, college_name, college_address, college_email, college_contact FROM colleges";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            echo '<table id="collegeTable" class="display">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>College Name</th>';
                            echo '<th>College Address</th>';
                            echo '<th>College Email</th>';
                            echo '<th>College Contact</th>';
                            echo '<th>Actions</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td data-id="' . $row['id'] . '" data-column="college_name">' . htmlspecialchars($row['college_name']) . '</td>';
                                echo '<td data-id="' . $row['id'] . '" data-column="college_address">' . htmlspecialchars($row['college_address']) . '</td>';
                                echo '<td data-id="' . $row['id'] . '" data-column="college_email">' . htmlspecialchars($row['college_email']) . '</td>';
                                echo '<td data-id="' . $row['id'] . '" data-column="college_contact">' . htmlspecialchars($row['college_contact']) . '</td>';
                                echo '<td>
                                        <span class="action-button edit-button" data-id="' . $row['id'] . '">Edit</span>
                                        <span class="action-button save-button" data-id="' . $row['id'] . '" style="display:none;">Save</span>
                                      </td>';
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo '<p>No colleges found.</p>';
                        }

                        $conn->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
    <footer>
         <div class="footer">
            <div class="contain">
               <div class="container">
                  <div class="row">
                     <div class="col-md-8 offset-md-2">
                        <p>Tech Fifo Innovations Pvt.Ltd. Copyrights© 2024 All Rights Reserved.<br>
                           Designed and developed by Tech Fifo Innovations</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>

    <!-- Javascript files-->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery-3.0.0.min.js"></script>
    <script src="../js/plugin.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#collegeTable').DataTable();

            // Edit button click handler
            $('.edit-button').click(function() {
                var id = $(this).data('id');
                var row = $(this).closest('tr');

                // Enable contenteditable for the row
                row.find('td[data-column]').attr('contenteditable', 'true').css('background-color', '#f9f9f9');

                // Show the Save button and hide the Edit button
                $(this).hide();
                row.find('.save-button').show();
            });

            // Save button click handler
            $('.save-button').click(function() {
                var id = $(this).data('id');
                var row = $(this).closest('tr');

                // Get the updated values
                var collegeName = row.find('td[data-id="' + id + '"][data-column="college_name"]').text();
                var collegeAddress = row.find('td[data-id="' + id + '"][data-column="college_address"]').text();
                var collegeEmail = row.find('td[data-id="' + id + '"][data-column="college_email"]').text();
                var collegeContact = row.find('td[data-id="' + id + '"][data-column="college_contact"]').text();

                // Send AJAX request to update the database
                $.ajax({
                    url: 'update_college.php',
                    type: 'POST',
                    data: {
                        id: id,
                        collegeName: collegeName,
                        collegeAddress: collegeAddress,
                        collegeEmail: collegeEmail,
                        collegeContact: collegeContact
                    },
                    success: function(response) {
                        alert(response);

                        // After saving, disable contenteditable
                        row.find('td[data-column]').attr('contenteditable', 'false').css('background-color', '');

                        // Show the Edit button and hide the Save button
                        row.find('.save-button').hide();
                        row.find('.edit-button').show();
                    },
                    error: function() {
                        alert('An error occurred while updating the college.');
                    }
                });
            });
        });

    
    function openNav() {
        document.getElementById("mySidepanel").style.width = "250px"; // Adjust the width as needed
    }

    function closeNav() {
        document.getElementById("mySidepanel").style.width = "0"; // Close the side panel
    }


    </script>
</body>
</html>
