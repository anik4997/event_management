<?php
session_start();
require_once 'classes/FetchEvent.php';
require_once 'classes/FetchUser.php';
require_once 'classes/AttendeeRegistration.php';
require_once 'classes/Search.php';

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Fetch the current user's role
$fetch_user_obj = new FetchUser();
$current_user = $fetch_user_obj->get_user_by_id($user_id);
$is_admin = $current_user['role'] == 1;

// Handle event search
$search_obj = new Search();
$search_result = null;
if (isset($_POST['search'])) {
    $search_result = $search_obj->event_search($_POST);
}

// Fetch events if no search result
$list_event_obj = new FetchEvent();
$events = $search_result ? $search_result : $list_event_obj->show_events();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="../style/style.css">
    <title>List of Events</title>
</head>
<body>
    <div class="row">
        <div class="col-md-5">
            <h2 class="d-inline">List of Events:</h2>
        </div>
        <div class="col-md-7">
            <form action="event_list.php" class="mb-5 search_form d-flex justify-content-end" method="post">
                <input type="date" class="form-control search_field me-2" name="start_date" placeholder="Start date" required>
                <input type="date" class="form-control search_field me-2" name="end_date" placeholder="End date" required>
                <button type="submit" class="btn btn-dark" name="search">Search</button>
            </form>
        </div>
    </div>
    <?php if ($is_admin): ?>
        <h3>Welcome admin</h3>
    <?php endif; ?>
    <p>Your user ID: <?php echo htmlspecialchars($user_id,$user_id); ?></p>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>


    <?php if ($is_admin): ?>
        <a href="download_report.php" class="btn btn-success mb-3">Download Report(CSV)</a>
        <a href="download_attendee_report.php" class="btn btn-secondary mb-3">Download All Attendee Report(CSV)</a>
        <a href="#"><button type="submit" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addeventModal" data-whatever="@mdo">Add Event</button></a>

        <!-- Add event Popup form start -->
        <div class="modal fade" id="addeventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="addEventForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addeventModal">Add an event</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                            <div class="modal-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Event Name</label>
                                        <input type="text" class="form-control" name="event_name" id="event_name" aria-describedby="emailHelp" placeholder="Enter event name" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Place</label>
                                        <input type="text" class="form-control" name="place" id="place" aria-describedby="emailHelp" placeholder="Place of the event" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Max capacity</label>
                                        <input type="number" class="form-control" name="max_capacity" id="max_capacity" aria-describedby="emailHelp" placeholder="Enter max capacity" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label">Event description:</label>
                                        <textarea class="form-control" name="event_description" id="event_description" placeholder="Enter description" required></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="startDate">Event date</label>
                                        <input id="event_date" class="form-control" name="event_date" type="date" required />
                                        <div class="invalid-feedback"></div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="addevent">Add This Event</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Add event Popup form end -->
    <?php endif; ?>

    <table class="table table-bordered" id="data_table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Sl no</th>
                <th scope="col">Name of Events</th>
                <th scope="col">Place</th>
                <th scope="col">Description</th>
                <th scope="col">Event date</th>
                <th scope="col">No of attendees/Max capacity</th>
                <th scope="col">Download attendee report</th>
                <th scope="col">Attendee registration</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if ($events){
                    $sl_no = 0;
                    while($row = mysqli_fetch_assoc($events)){
                        $sl_no++;
                        $attendee_data = $list_event_obj->attendee_list($row['id']);
                        if($attendee_data == false){
                            $attendee_count = 0;
                        }else{
                            $attendee_count = mysqli_num_rows($attendee_data);
                            $attendee_info = mysqli_fetch_assoc($attendee_data);
                        }   
                ?>
                <tr>
                    <th scope="row"><?php echo $sl_no;?></th>
                    <td><?php echo $row['event_name'];?></td>
                    <td><?php echo $row['place'];?></td>
                    <td><?php echo $row['description'];?></td>
                    <td><?php echo $row['event_date'];?></td>
                    <td><?php echo $attendee_count . "/" . $row['max_capacity']; ?></td>
                    <td>
                        <?php if ($is_admin){ ?>
                            <a href="download_specific_attendee_report.php?event_id=<?php echo $row['id'];?>" class="btn btn-success mb-3">Download attendee Report</a>
                        <?php }else{ ?>
                            <a href="" class="btn btn-success mb-3" onclick="admin_alert(event)">Download attendee Report</a>
                        <?php } ?>

                    </td>
                    <td>
                        <?php
                        $AttendeeRegistration_obj = new AttendeeRegistration();
                        $is_already_registered = mysqli_num_rows($AttendeeRegistration_obj->get_previous_registration($user_id, $row['id'])) > 0;

                            if($attendee_count >= $row['max_capacity']){
                        ?>
                            <span class="btn btn-danger">Max-capacity exceeded</span>
                        <?php
                            }elseif($is_already_registered){
                                ?>
                            <span class="btn btn-danger">Already Registered</span>
                                <?php
                            }
                            else{
                        ?>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal_<?php echo $row['id']; ?>" data-whatever="@mdo">Attendee Registration</button>
                        <?php
                            }
                        ?>
                        
                        <!-- Attendee registration Popup form start -->
                        <div class="modal fade" id="exampleModal_<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form id="attendee_registration">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel_<?php echo $row['id']; ?>">Book your presence</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                            <div class="modal-body">
                                                    <input type="hidden" name="event_id" value="<?php echo $row['id'];?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                                                    <div class="form-group">
                                                        <label for="recipient-name_<?php echo $row['id']; ?>" class="col-form-label">Name of attendee:</label>
                                                        <input type="text" class="form-control" id="recipient-name_<?php echo $row['id']; ?>" name="name_attendee" required>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="attendee-phone_<?php echo $row['id']; ?>" class="col-form-label">Phone:</label>
                                                        <input type="number" class="form-control" id="attendee-phone_<?php echo $row['id']; ?>" name="phone_attendee" required>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="attendee-email_<?php echo $row['id']; ?>" class="col-form-label">Email:</label>
                                                        <input type="email" class="form-control" id="attendee-email_<?php echo $row['id']; ?>" name="email_attendee" required>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="message-text_<?php echo $row['id']; ?>" class="col-form-label">Write your opinion:</label>
                                                        <textarea class="form-control" id="message-text_<?php echo $row['id']; ?>" name="attendee_opinion"></textarea>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" name="submitAttendeeInfo">Book</button>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Attendee registration Popup form end -->
                    </td>
                    <td>
                    <?php if ($is_admin): ?>
                            <!-- Edit modal start -->
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal_<?php echo $row['id']; ?>" data-whatever="@mdo">Edit</button>

                            <!-- Edit event Popup form start -->
                            <div class="modal fade" id="editModal_<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <form class="editEventForm">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addeventModal">Edit this event</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="event_id" value="<?php echo $row['id'];?>">
                                                    <input type="hidden" name="attendee_count" value="<?php echo $attendee_count;?>">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Event Name</label>
                                                            <input type="text" class="form-control" name="event_name" id="event_name" aria-describedby="emailHelp" placeholder="Enter event name" value="<?php echo $row['event_name'];?>" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Place</label>
                                                            <input type="text" class="form-control" name="place" id="place" aria-describedby="emailHelp" placeholder="Place of the event" value="<?php echo $row['place'];?>" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Max capacity</label>
                                                            <input type="number" class="form-control" name="max_capacity" id="max_capacity" aria-describedby="emailHelp" placeholder="Enter max capacity" value="<?php echo $row['max_capacity'];?>" required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="message-text" class="col-form-label">Event description:</label>
                                                            <textarea class="form-control" name="event_description" id="event_description" placeholder="Enter description" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="startDate">Event date</label>
                                                            <input id="event_date" class="form-control" name="event_date" type="date" value="<?php echo $row['event_date'];?>" required />
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" name="addevent">Edit This Event</button>
                                                </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            <!-- Edit event Popup form end -->
                            <!-- Edit modal end -->

                            <a href="#" class="btn btn-danger delete-event" data-event-id="<?php echo $row['id']; ?>">Delete</a>
                        <?php else: ?>
                            <a href='#' class="btn btn-warning" onclick="admin_alert(event)">Edit</a>
                            <a href='#' class="btn btn-danger" onclick="admin_alert(event)">Delete</a>
                            <script>
                                function admin_alert(event) {
                                    event.preventDefault();
                                    alert("You must be an admin to perform this action!");
                                }
                            </script>
                    <?php endif; ?>
                    <!-- View event start -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewModal_<?php echo $row['id']; ?>" data-whatever="@mdo">View</button>

                    <!-- View event Popup form start -->
                    <div class="modal fade" id="viewModal_<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel_<?php echo $row['id']; ?>">View event details</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                            <div class="modal-body">
                                                    <input type="hidden" name="event_id" value="<?php echo $row['id'];?>">
                                                    <div class="form-group">
                                                        <h6>Event name:</h6>
                                                        <label for="recipient-name_<?php echo $row['id']; ?>" class="col-form-label"><?php echo $row['event_name'];?></label>
                                                    </div>
                                                    <div class="form-group">
                                                        <h6>Event place:</h6>
                                                        <label for="attendee-phone_<?php echo $row['id']; ?>" class="col-form-label"><?php echo $row['place'];?></label>
                                                    </div>
                                                    <div class="form-group">
                                                        <h6>Event Date:</h6>
                                                        <label for="attendee-email_<?php echo $row['id']; ?>" class="col-form-label"><?php echo $row['event_date'];?></label>
                                                    </div>
                                                    <div class="form-group">
                                                        <h6>Registered attendee/Max capacity:</h6>
                                                        <label for="message-text_<?php echo $row['id']; ?>" class="col-form-label"><?php echo $attendee_count . "/" . $row['max_capacity']; ?></label>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                </div>
                            </div>
                        </div>
                        <!-- View event Popup form end -->
                        <!-- View event end -->
                    </td>
                </tr>
            <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No events found</td></tr>";
                }
            ?>
        </tbody>
    </table>
    <a href="logout.php"><button type="submit" class="btn btn-danger">Logout</button></a>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script>
        $(document).ready( function () {
            // datatable
            $('#data_table').DataTable();

                // add event ajax start
                $('#addEventForm').on('submit', function (e) {
                e.preventDefault(); 

                $('.invalid-feedback').text('');
                $('.form-control').removeClass('is-invalid');

                let eventData = {
                    event_name: $('input[name="event_name"]').val(),
                    place: $('input[name="place"]').val(),
                    max_capacity: $('input[name="max_capacity"]').val(),
                    event_description: $('textarea[name="event_description"]').val(),
                    event_date: $('input[name="event_date"]').val()
                };

                // Collect form data
                let formData = $(this).serialize();

                // Send data via AJAX
                $.ajax({
                    url: 'addevent_ajax.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            location.reload(); 
                        } else {
                            for (let field in data.errors) {
                                let errorMessage = data.errors[field];
                                $(`#${field}`).addClass('is-invalid'); 
                                $(`#${field}`).next('.invalid-feedback').text(errorMessage); 
                            }
                        }
                    },
                    error: function () {
                        alert('An error occurred while adding the event.');
                    }
                });

            });
        });
        // add event ajax end


        // edit event ajax start
        $(document).on('submit', '.editEventForm', function (e)  {
        e.preventDefault();

        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');

        let formData = $(this).serialize(); 

        $.ajax({
            url: 'editevent_ajax.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log("AJAX Response:", data);
                if (data.success) {
                    location.reload(); 
                } else if (data.errors) { 
                    for (let field in data.errors) {
                        let errorMessage = data.errors[field];
                        $(`[name="${field}"]`).addClass('is-invalid'); 
                        $(`[name="${field}"]`).next('.invalid-feedback').text(errorMessage);
                    }
                } else {
                    alert('Unknown error occurred!');
                }
            },
            error: function () {
                alert('An error occurred while editing this event.');
            }
        });
    });

        // edit event ajax end


        // attendee registration ajax start
        $(document).on('submit', '#attendee_registration', function (e) {
            e.preventDefault(); 
            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');

            let formData = $(this).serialize(); 

            $.ajax({
                url: 'attendee_registration_ajax.php', 
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        if (response.errors) {
                            for (let field in response.errors) {
                                let errorMessage = response.errors[field];
                                $(`[name="${field}"]`).addClass('is-invalid'); 
                                $(`[name="${field}"]`).next('.invalid-feedback').text(errorMessage);
                            }
                        } else {
                            alert(response.error);
                        }
                    }
                },
                error: function () {
                    alert('An error occurred while regitered the attendee.');
                }
            });
        });
        // attendee registration ajax end

        // Delete event ajax start
        $(document).on('click', '.delete-event', function (e) {
        e.preventDefault();

        if (!confirm('Are you sure you want to delete this event?')) {
            return;
        }

        let eventId = $(this).data('event-id');
        
        $.ajax({
            url: 'delete_event.php',
            type: 'POST',
            data: { event_id: eventId },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $(`a[data-event-id="${eventId}"]`).closest('tr').fadeOut(300, function () {
                        $(this).remove(); 
                    });
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function () {
                alert('An error occurred while deleting the event.');
            }
        });
    });
    // Delete event ajax end

    </script>
</body>
</html>
