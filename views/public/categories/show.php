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


<div id="categories-tree" style="margin-top:50px;">
<?php if (count($tree)): ?>    
    <b><?php echo __('Tree') ?></b><br />
    <?php foreach($tree as $category): ?>
        <a style="margin-left:<?php echo ((integer)$category->level * 20)-20 ?>px"  href="<?php echo $category->getUrl() ?>"><?php echo $category->title ?></a> 
        (<?php echo count($category->pages) ?>)
        <?php if ($category->current): ?>
            ***
        <?php endif; ?>    
        <br />
    <?php endforeach; ?>    
<?php endif; ?>    
</div>


<?php echo foot(); ?>
