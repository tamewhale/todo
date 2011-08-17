$(document).ready( function() {

    // tie form submission to ajax
    $('#submit').click( function() {
        task = $('#task_name').val();
        day = $('#day :selected').val();
        month = $('#month :selected').val();
        year = $('#year :selected').val();
        var dataString = 'task='+ task + '&day=' + day + '&month=' + month + '&year=' + year;
        $.ajax({  
            type: "POST",  
            url: "ajax/add_task.php",  
            data: dataString,  
            success: formSuccess
        });  
        return false;  
    });

    // function to display error
    function displayError(errorMsg) {
        // remove existing errors
        $('.error').remove();
        // create new one
        $('.help').after('<p class="error">' + errorMsg + '</p>');
    };
    
    // function on form success
    function formSuccess(data) {
        if(data.substr(0, 5) === 'error') {
            errorMsg = data.substring(6);
            displayError(errorMsg);
        }
        else {
            $('#task-list').append(data);
            $('#task_name').val('').focus();
        }
    };
    
    // put focus on first form field
    $('#task_name').focus();

});