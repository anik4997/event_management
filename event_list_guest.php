<?php
require_once 'classes/FetchEvent.php';
require_once 'classes/Search.php';
require_once 'classes/AttendeeRegistration.php';


$list_event_obj = new FetchEvent();
$AttendeeRegistration_obj = new AttendeeRegistration();


// Check if search form is submitted
$search_obj = new Search();
$search_result = null;
if (isset($_POST['search'])) {
    $search_result = $search_obj->event_search($_POST);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/style.css">
    <title>List of events</title>
</head>
<body>
    <div class="row">
        <div class="col-md-5">
            <h2 class="d-inline">List of events:</h2>
        </div>
        <div class="col-md-7">
            <form action="event_list_guest.php" class="mb-5 search_form d-flex justify-content-end" method="post">
                <input type="date" class="form-control search_field me-2" name="start_date" placeholder="Start date" required>
                <input type="date" class="form-control search_field me-2" name="end_date" placeholder="End date" required>
                <button type="submit" class="btn btn-dark" name="search">Search</button>
            </form>
        </div>
    </div>
    
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Sl no</th>
                <th scope="col">Name of Events</th>
                <th scope="col">Place</th>
                <th scope="col">Description</th>
                <th scope="col">Event date</th>
                <th scope="col">No of attendees/Max capacity</th>
                <th scope="col">Attendee registration</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $events = [];
                if ($search_result) {
                    // Fetch data from search results
                    while ($row = mysqli_fetch_assoc($search_result)) {
                        $events[] = $row;
                    }
                } else {
                    // Fetch all Events if no search input
                    $fetch_event_obj = new FetchEvent();
                    $show_event = $fetch_event_obj->show_events();
                    if ($show_event) {
                        while($row = mysqli_fetch_assoc($show_event)){
                            $events[] = $row;
                        }
                    }
                }

                if (!empty($events)) {
                    $sl_no = 0;
                    foreach ($events as $row) {
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
                    <?php
                            if($attendee_count >= $row['max_capacity']){
                        ?>
                            <span class="btn btn-danger">Max-capacity exceeded</span>
                        <?php
                            }else{
                        ?>
                            <button type="button" class="btn btn-primary" onclick="login_alert(event)">Attendee Registration</button>
                        <?php
                            }
                        ?>
                        <script>
                            function login_alert(event) {
                                event.preventDefault(); 
                                alert("You must login to perform this action");
                            }
                        </script>
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
    <a href="index.php"><button type="submit" class="btn btn-primary">Login</button></a>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> 
</body>
</html>
