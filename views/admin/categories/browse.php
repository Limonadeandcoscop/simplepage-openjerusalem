<?php
$pageTitle = __('Browse Categories');
echo head(array('title'=>$pageTitle,'bodyclass' => 'items browse'));
?>

<h1><?php echo $pageTitle;?> <?php echo __('(%s total)', $total_results); ?></h1>

<?php echo flash(); ?>

<a class="add-page button small green" href="<?php echo html_escape(url('simple-pages/categories/add')); ?>"><?php echo __('Add a Category'); ?></a>

<?php echo pagination_links(); ?>

<?php if(count($simple_pages_categories)): ?>
<table class="full">
    <thead>
        <tr>
            <?php echo browse_sort_links(array(
                __('Title') => 'title',
                __('Slug') => 'slug',
                __('Last Modified') => 'updated'), array('link_tag' => 'th scope="col"', 'list_tag' => ''));
            ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($simple_pages_categories as $category): ?>
        <tr>
            <td>
                <span class="title">
                    <a href="<?php echo html_escape(record_url($category)); ?>">
                        <?php echo $category->title; ?>
                    </a>
                </span>
                <ul class="action-links group">
                    <li><a href="<?php echo html_escape(url('simple-pages/categories/edit/id/').$category->id); ?>">
                        <?php echo __('Edit'); ?>
                    </a></li>
                    <li><a class="delete-confirm" href="<?php echo html_escape(url('simple-pages/categories/delete-confirm/id/').$category->id); ?>">
                        <?php echo __('Delete'); ?>
                    </a></li>
                </ul>
            </td>
            <td><?php echo metadata($category, 'slug'); ?></td>
            <?php $modified_user = get_record_by_id("User", $category->modified_by_user_id); ?>
            <td><?php echo __('<strong>%1$s</strong> on %2$s',
                $modified_user->name,
                html_escape(format_date($category->updated, Zend_Date::DATETIME_SHORT))); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>



<?php echo pagination_links(); ?>

<?php fire_plugin_hook('public_simple_page_categories_browse', array('items'=>$simple_pages_categories, 'view' => $this)); ?>

<?php echo foot(); ?>
