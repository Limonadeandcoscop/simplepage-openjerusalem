<?php
$bodyclass = 'page simple-page';
if ($is_home_page):
    $bodyclass .= ' simple-page-home';
endif;

echo head(array(
    'title' => metadata('simple_pages_page', 'title'),
    'bodyclass' => $bodyclass,
    'bodyid' => metadata('simple_pages_page', 'slug')
));
?>
<div id="primary">

    <?php if (!$is_home_page): ?>
    <p id="simple-pages-breadcrumbs">
        <?php echo simple_pages_categories_display_breadcrumbs($simple_pages_page->category_id) ?> > <?php echo metadata('simple_pages_page', 'title'); ?>
    </p>
    <?php endif; ?>

    <h1><?php echo metadata('simple_pages_page', 'title'); ?></h1>
    <?php
        $text = metadata('simple_pages_page', 'text', array('no_escape' => true));
        echo $this->shortcodes($text);
    ?>
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
