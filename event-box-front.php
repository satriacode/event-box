<?php

class EventBoxFront {

    function event_box_shortcode($atts = '')
    {
        ob_start();

        extract(shortcode_atts(array(
            'id' => 1,
        ), $atts));

        $this->event_box_form($id);

        return ob_get_clean();
    }

    function event_box_form($sc_id)
    {
        global $wpdb;
        global $first_name, $last_name, $email, $phone, $comment, $event_date;

        $results = $wpdb->get_results("SELECT * FROM " . TABLE_SETUP . " WHERE id='$sc_id'");

        if(count($results) > 0) {
            $event = $results[0];
            $id_e = $event->id;
            $title_e = $event->title;
            $place_e = $event->place;
            $body_e = $event->body;
            $quota_e = $event->quota;
            $event_date_e = $event->event_date;
            $status_e = $event->status;
        }

        echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <div id="respond" class="comment-respond" style="background-color:#fbfbfb; padding:30px; margin:1px;">
                <form id="event_form" action="" class="comment-form" method="post">
                    <h3> ' . $title_e . '</h3>
                    <hr/>
                    <h5> ' . $body_e . '</h5>
                    <hr/>
                    <input type="hidden" name="classification" value="1"/>
                    <input type="hidden" name="form_id" value="' . $id_e . '"/>
                    <div>
                        <label for="first_name">First Name <strong style="color:red">*</strong></label>
                        <input type="text" required class="search-field" name="first_name" value="' . (isset($_POST['first_name']) ? $first_name : null) . '"
                    </div>
                    <div>
                        <label for="last_name">Last Name <strong style="color: red">*</strong></label>
                        <input type="text" required name="last_name" value="' . ( isset( $_POST['last_name'] ) ? $last_name : null ) . '">
                    </div>
                    <div>
                        <label for="email">Email <strong style="color: red">*</strong></label>
                        <input type="email" required name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '">
                    </div>
                    <div>
                        <label for="website">Phone Number <strong style="color: red">*</strong></label>
                        <input type="number" required name="phone" value="' . ( isset( $_POST['phone']) ? $phone : null ) . '">
                    </div>
                    <div>
                        <label for="service_day">Service Date <strong style="color: red">*</strong></label>
                        <input type="text" required name="service_day" readonly value="' .date('d-m-Y', strtotime($event_date_e)). '">
                    </div>
                    <div>
                        <label for="bio">If you have further enquires, please inform us below</label>
                        <textarea name="comment">' . ( isset( $_POST['comment']) ? $comment : null ) . '</textarea>
                    </div>
                    <hr />
                    <div>
                        <input type="submit" name="submit" value="Register For Event"/>
                    </div>
                </form>
            </div>
            <script type="text/javascript">
        jQuery("#event_form").submit(function(event){
            event.preventDefault();
            var post_url = jQuery(this).attr("action");
            var request_method = jQuery(this).attr("method");
            var form_data = jQuery(this).serialize();
            console.log(post_url);
            console.log(form_data);
            jQuery.ajax({
                url : post_url,
                type: request_method,
                cache : false,
                datatype : "json",
                async : false,
                data : form_data
            }).done(function(response){
                console.log(response);
                Swal.fire({
                    title: "Success!",
                    text: "Your registration was successful",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById("event_form").reset();
                    }
                })
            }).fail(function(response){
                console.log(response);
                Swal.fire({
                    title: "Error!",
                    text: "Your registration was not successful",
                    icon: "error",
                    confirmButtonText: "OK"
                })
            });
        })
    </script>
        ';
    }
}

if(isset($_POST['first_name'])){
    global $wpdb;
    global $first_name, $last_name, $email, $phone, $comment, $classification, $attendant, $event_date;
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $phone = sanitize_text_field($_POST['first_name']);
    $email = sanitize_text_field($_POST['email']);
    $event_date = sanitize_text_field($_POST['event_date']);
    $form_id = sanitize_text_field($_POST['form_id']);
    $event_date_show = date('Y-m-d', strtotime($_POST['event_date']));
    $comment = esc_textarea($_POST['comment']);
    $userdata = array(
        'form_id' => $form_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone,
        'event_date' => $event_date_show,
        'comment' => $comment
    );
    $result_check = $wpdb->insert(
        TABLE_REGISTRATION,
        $userdata
    );
    if($result_check) {
        $result['success'] = 1;
        $result['message'] = "Your registration was successful";
    } else {
        $result['success'] = 0;
        $result['message'] = "Your registration was unsuccessful";
    }

    echo json_encode($result);
    die();
}