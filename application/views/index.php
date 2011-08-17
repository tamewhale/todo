<!DOCTYPE html>
<html lang="en">
<head>

    <title>To-do List</title>

    <meta name="description" content="A simple to-do list application" />
    <meta charset="utf-8" />

	<link rel="stylesheet" href="<?php echo base_url() . 'css/style.css'; ?>" />
    <!-- Grab latest version of jQuery -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <!-- <script src="<?php echo base_url() . 'js/todo.js'; ?>"></script> -->

</head>
<body>
<div id="wrapper">

<h1>To-do List</h1>

<p class="help">Click on a task to mark as complete. Overdue tasks are highlighted in a nice <span>reddish-pink</span> colour.</p>

<?php if($errors): ?>
<div class="error"><?php echo validation_errors(); ?></div>
<?php endif; ?>

<?php if(empty($tasks)): ?>

<p>There are currently no tasks.</p>

<?php else: ?>

<ul id="task-list">

<?php foreach($tasks as $task): ?>
<li class="<?php echo $task['class']; ?>">
	<?php echo anchor("tasklist/complete/{$task['id']}", $task['task_name'], array('title' => 'click to mark as complete')); ?>
    <span><?php echo $task['task_due']; ?></span>
</li>
<?php endforeach; ?>

</ul>

<?php endif; ?>

<?php echo form_open('tasklist/add', array('id' => 'form-add')); ?>
    
    <fieldset>

    <input type="text" name="task_name" id="task_name" value="<?php echo set_value('task_name'); ?>" placeholder="Enter task and choose a date." maxlength="50" />

    <select name="day" id="day">
    <?php foreach($days as $day): ?>
        <option value="<?php echo $day; ?>" <?php echo set_select('day', $day); ?>><?php echo $day; ?></option>
    <?php endforeach; ?>
    </select>
    
    <select name="month" id="month">
    <?php foreach($months as $month): ?>
        <option value="<?php echo $month; ?>" <?php echo set_select('month', $month); ?>><?php echo $month; ?></option>
    <?php endforeach; ?>
    </select>
    
    <select name="year" id="year">
    <?php foreach($years as $year): ?>
		<option value="<?php echo $year; ?>" <?php echo set_select('year', $year); ?>><?php echo $year; ?></option>
    <?php endforeach; ?>
    </select>
    
    <input type="submit" name="submit" id="submit" value="Add Task" />
    
    </fieldset>

<?php echo form_close(); ?>
	
</div> <!-- end #wrapper -->
</body>
</html>