<?php
$bodyclass = 'page simple-page';
if ($is_home_page):
    $bodyclass .= ' simple-page-home';
endif;

$category = $this->simple_pages_category;

echo head(array(
    'title' => $category->title,
    'bodyclass' => $bodyclass,
    'bodyid' => $category->slug,
));
?>
<div id="primary">
    <?php if (!$is_home_page): ?>
        <p id="simple-pages-breadcrumbs"><?php echo simple_pages_categories_display_breadcrumbs(); ?></p>
    <?php endif; ?>
</div>

<div id="category-pages">
    <?php if (count($pages)): ?>    
    <b><?php echo __('Pages of this category') ?></b><br />
    <?php foreach($pages as $page): ?>
        <a href="<?php echo $page->getRecordUrl() ?>"><?php echo $page->title ?></a><br />
    <?php endforeach; ?>    
<?php endif; ?>    
</div>    


<div id="parents-categories">
<?php if (count($parents)): ?>    
    <b><?php echo __('Parents categories') ?></b><br />
    <?php foreach($parents as $category): ?>
        <a href="<?php echo $category->getUrl() ?>"><?php echo $category->title ?></a><br />
    <?php endforeach; ?>    
<?php endif; ?>    
</div>


<div id="children-categories">
<?php if (count($children)): ?>    
    <b><?php echo __('Children categories') ?></b><br />
    <?php foreach($children as $category): ?>
        <a  href="<?php echo $category->getUrl() ?>"><?php echo $category->title ?></a><br />
    <?php endforeach; ?>    
<?php endif; ?>    
</div>

<?php echo foot(); ?>
