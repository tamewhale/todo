<?php
require_once('../config.php');
if($_POST['task'] && $_POST['day'] && $_POST['month'] && $_POST['year']) {
    // check if task name is blank
    if(!strlen(trim($_POST['task'])) || $_POST['task'] == 'Enter task and choose a date.') {
        echo 'error:You haven\'t given your task a name.';
    }
    // check if date entries are numbers
    elseif(!ctype_digit($_POST['day']) || !ctype_digit($_POST['month']) || !ctype_digit($_POST['year'])) {
        echo 'error:The date information you supplied was corrupted.';
    }
    // check date is a real date ie not 30 Feb
    elseif(!checkdate($_POST['month'], $_POST['day'], $_POST['year'])) {
        echo 'error:That date is not valid.';
    }
    // check date is in the future
    // if the date is today that is OK so I'll compare current timestamp with timestamp for the end of the day
    elseif(mktime(23, 59, 59, $_POST['month'], $_POST['day'], $_POST['year']) < time()) {
        echo 'error:The date you\'ve given is in the past.';
    }
    // if we pass all these checks, add the data to the db
    else {
        // insert into database
        // using prepared statement purely to avoid sql injections
        $task_name = trim($_POST['task']);
        $due_date = "{$_POST['year']}-{$_POST['month']}-{$_POST['day']} 23:59:59";
        $sql = "INSERT INTO tasks VALUES(null, ?, ?, null)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ss', $task_name, $due_date);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        $due_time = mktime(0, 0, 0, $_POST['month'], $_POST['day'], $_POST['year']);
        $due_date = date('jS M, Y', $due_time);
        echo "<li><a href=\"index.php?action=complete&amp;id=$id\" title=\"click to mark as complete\">$task_name</a><span>$due_date</span></li>";
    }
}
else {
    echo 'error';
}
?>
