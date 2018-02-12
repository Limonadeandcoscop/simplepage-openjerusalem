<?php

$head = array('bodyclass' => 'simple-pages primary', 
              'title' => html_escape(__('Tags | Add tag')));
echo head($head);
?>

<?php echo flash(); ?>
<?php echo $form; ?>
<?php echo foot(); ?>


