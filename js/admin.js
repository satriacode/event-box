jQuery(document).ready(function($) {
    // Hide all tabs
    hideAll();

    // Show the first tab
    $('#form-tab').show();

    $('#form-tab-link').addClass('nav-tab-active');

    function hideAll() {
        $('#event-list-tab').hide();
        $('#form-tab').hide();
        $('#attendees-tab').hide();
    }

    function allTabInactive() {
        $('#form-tab-link').removeClass('nav-tab-active');
        $('#event-list-tab-link').removeClass('nav-tab-active');
        $('#attendees-tab-link').removeClass('nav-tab-active');
    }

    // When a tab is clicked
    $('#form-tab-link').click(function(e) {
        // Prevent the default action
        e.preventDefault();

        // Remove the 'nav-tab-active' class from all tabs
        allTabInactive();

        // Add the 'nav-tab-active' class to this tab
        $(this).addClass('nav-tab-active');

        // Hide all tab content
        hideAll();

        // Show the associated tab content
        $($(this).attr('href')).show();
    });
    $('#event-list-tab-link').click(function(e) {
        // Prevent the default action
        e.preventDefault();

        // Remove the 'nav-tab-active' class from all tabs
        allTabInactive();

        // Add the 'nav-tab-active' class to this tab
        $(this).addClass('nav-tab-active');

        // Hide all tab content
        hideAll();

        // Show the associated tab content
        $($(this).attr('href')).show();
    });
    $('#attendees-tab-link').click(function(e) {
        // Prevent the default action
        e.preventDefault();

        // Remove the 'nav-tab-active' class from all tabs
        allTabInactive();

        // Add the 'nav-tab-active' class to this tab
        $(this).addClass('nav-tab-active');

        // Hide all tab content
        hideAll();

        // Show the associated tab content
        $($(this).attr('href')).show();
    });
});