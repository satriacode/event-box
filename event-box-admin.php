<?php

class EventBoxAdmin {

    function event_box_admin_page()
    {
        error_log('admin page');
        global $wpdb;
        $table_setup = $wpdb->prefix . PLUGIN_PREFIX . 'event_setup';
        $table_attendees = $wpdb->prefix . PLUGIN_PREFIX . 'attendee_registration';

        if (isset($_POST['newsubmit'])) {
            $uptid = $_POST['uptid'];
            $title = $_POST['title'];
            $place = $_POST['place'];
            $body = $_POST['body'];
            $quota = $_POST['quota'];
            $event_date = $_POST['event_date'];
            $status = $_POST['status'];

            if (empty($uptid)) {
                $wpdb->query("INSERT INTO $table_setup(title, place, body, quota, event_date,status) VALUES ('$title', '$place', '$body', '$quota', '$event_date', '$status')");
            } else {
                $wpdb->query("UPDATE $table_setup SET title='$title', place='$place', body='$body', quota='$quota', event_date='$event_date', status='$status' WHERE id='$uptid'");
            }

            echo "Submitted successfully";
        }
        if (isset($_GET['upt'])) {
            $upt_id = $_GET['upt'];
            $results = $wpdb->get_results("SELECT * FROM $table_setup WHERE id = '$upt_id'");
            if (count($results) > 0) {
                $event = $results[0];
                $id_e = $event->id;
                $title_e = $event->title;
                $place_e = $event->place;
                $body_e = $event->body;
                $quota_e = $event->quota;
                $event_date_e = $event->event_date;
                $status_e = $event->status;
            }
        } else if (isset($_GET['del'])) {
            $del_id = $_GET['del'];
            $wpdb->query("DELETE FROM $table_setup WHERE id='$del_id'");
        }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <h2 class="nav-tab-wrapper">
            <a href="#form-tab" class="nav-tab" id="form-tab-link">Form</a>
            <a href="#event-list-tab" class="nav-tab" id="event-list-tab-link">Event List</a>
            <a href="#attendees-tab" class="nav-tab" id="attendees-tab-link">Attendees</a>
        </h2>
        <div id="form-tab">
            <form action="" method="post">
                <input type="hidden" id="uptid" name="uptid" value="<?php echo isset($id_e)? $id_e: ''; ?>"/>
                <div class="wrap">
                    <h2><?php echo isset($id_e) ? "Editing: $title_e" : 'Setup Event Registration'; ?></h2>
                    <div id="post-body-content" class="edit-form-section edit-comment-section" style="max-width: 50%; padding: 10px;">
                        <div class="inside">
                            <div id="comment-link-box">

                            </div>
                        </div>
                        <div id="namediv" class="stuffbox">
                            <div class="inside">
                                <h2 style=" padding: 10px;" class="edit-comment-author">Event Registration</h2>
                                <fieldset>
                                    <legend class="screen-reader-text">Event Registration</legend>
                                    <table class="form-table editcomment" role="presentation">
                                        <tbody>
                                            <tr>
                                                <td class="first"><label for="name">Title</label></td>
                                                <td><input type="text" required name="title" size="30" value="<?php echo isset($title_e)? $title_e : ''; ?>" id="title"></td>
                                            </tr>
                                            <tr>
                                                <td class="first"><label for="name">Place</label></td>
                                                <td><input type="text" required name="place" size="30" value="<?php echo isset($place_e)? $place_e : ''; ?>" id="place"></td>
                                            </tr>
                                            <tr>
                                                <td class="first"><label for="email">Quota</label></td>
                                                <td>
                                                    <input type="number" required name="quota" size="30" value="<?php echo isset($quota_e)? $quota_e : '100'; ?>" id="quota">
                                                </td>
                                            </tr>
                                            <tr>
                                                <?php $nextSunday = date('Y-m-d', strtotime('next sunday')); ?>
                                                <td class="first"><label for="name">Date</label></td>
                                                <td><input type="date" required name="event_date" value="<?php echo isset($event_date_e)? $event_date_e : $nextSunday; ?>" id="event_date"></td>
                                            </tr>
                                            <tr>
                                                <td class="first"><label for="body">Body</label></td>
                                                <td>

                                                    <?php
                                                    $settings = array('teeny' => true, 'textarea_rows' => 10, 'tabindex' => 1);
                                                    wp_editor(esc_html((get_option('text', isset($body_e) ? strip_tags($body_e) : ''))), 'body', $settings);
                                                    ?>

                                                    <!-- <textarea style="width: 100%" name="body" id="body">  </textarea> -->
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="first"><label for="email">Status</label></td>
                                                <td>
                                                    <select name="status" required id="status">
                                                        <option value="0" <?php echo isset($status_e) && $status_e == 0 ? 'selected' : ''; ?>>Disabled</option>
                                                        <option value="1" <?php echo isset($status_e) && $status_e == 1 ? 'selected' : ''; ?>>Active</option>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td> <button id="newsubmit" name="newsubmit" class="button button-success" type="submit"><?php echo isset($id_e)? 'Update' : 'Create'; ?></button> </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="event-list-tab">
            <div id="event-list" class="wrap wp-submenu-head widefat">
                <h2>Created Events</h2>
                <table class="wp-list-table widefat striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Place</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Quota Size</th>
                            <th>Body</th>
                            <th>Shortcode</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $results = $wpdb->get_results("SELECT * FROM $table_setup ORDER BY id DESC");
                            foreach ($results as $print) {

                                $main_body = substr(strip_tags($print->body), 0, 50);
                                $shortcode = "[event_box id=$print->id]";
                                if ($print->status == 0) {
                                    $status = "<span style='color:red'>Disabled</span>";
                                } elseif ($print->status == 1) {
                                    $status = "<span style='color:darkgreen'>Active</span>";
                                } else {
                                    $status = "<span style='color:darkorange'>Closed</span>";
                                }

                                echo "
                                    <tr>
                                    <td>$print->id</td>
                                    <td>$print->title</td>
                                    <td>$print->place</td>
                                    <td>$print->event_date</td>
                                    <td>$status</td>
                                    <td>$print->quota</td>
                                    <td>$main_body</td>
                                    <td>$shortcode</td>
                                    <td>
                                        <a class='button' style='color:#fff; background-color:green' href='admin.php?page=event-box%2Fevent-box.php&upt=$print->id'>Update</a>
                                        <a class='button' style='color:#fff; background-color:red' href='admin.php?page=event-box%2Fevent-box.php&del=$print->id'>Delete</a>
                                    </td>
                                    </tr>
                                ";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="attendees-tab">
            <div id="attendee" class="wrap wp-submenu-head widefat">
                <h2>List of attendees</h2>
                <table class="wp-list-table widefat striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Comment</th>
                            <th>Register At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $results = $wpdb->get_results("SELECT a.user_id, s.title, CONCAT(a.first_name, ' ', a.last_name) as name, a.email, a.phone, a.comment, a.creation_time FROM $table_attendees as a JOIN $table_setup as s ON a.form_id = s.id  ORDER BY id DESC");
                            foreach ($results as $print) {

                                $comment = substr(strip_tags($print->comment), 0, 50);

                                echo "
                                    <tr>
                                    <td>$print->user_id</td>
                                    <td>$print->title</td>
                                    <td>$print->name</td>
                                    <td>$print->email</td>
                                    <td>$print->phone</td>
                                    <td>$comment</td>
                                    <td>$print->creation_time</td>
                                    </tr>
                                ";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    }
}