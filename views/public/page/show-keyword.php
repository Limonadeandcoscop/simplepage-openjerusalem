<?php
$bodyclass = 'keyword simple-page';

echo head(array(
    'title' => $keyword->name,
    'bodyclass' => $bodyclass,
));
?>
<div id="primary">
  
    <p id="simple-pages-breadcrumbs">
        <?php echo simple_pages_categories_display_breadcrumbs(null, " > ", 'with_items') ?> > <?php echo __('Tag')  ?> "<?php echo $keyword->name; ?>"
    </p>
    
    <h1><?php echo $keyword->name ?></h1>

    <div id="keyword-pages">
	    <?php if (count($pages)): ?>    
	    <b><?php echo __('Pages of this tag') ?></b><br />
	    <?php foreach($pages as $page): ?>
	        <a href="<?php echo $page->getRecordUrl() ?>"><?php echo $page->title ?></a><br />
	    <?php endforeach; ?>    
	<?php endif; ?>    
	</div>    
	<br><br>

    <a href="javascript:history.back();"><?php echo __('Back') ?></a>
</div>


<?php echo foot(); ?>
