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

<?php echo foot(); ?>
