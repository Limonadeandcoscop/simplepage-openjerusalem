<?php
$pageTitle = __('Browse Categories');
echo head(array('title'=>$pageTitle,'bodyclass' => 'items browse'));
?>

<ul id="section-nav" class="navigation">
    <li class="<?php if (isset($_GET['view']) &&  $_GET['view'] != 'hierarchy') {echo 'current';} ?>">
        <a href="<?php echo html_escape(url('simple-pages/index/browse?view=list')); ?>"><?php echo __('List View'); ?></a>
    </li>
    <li class="<?php if (isset($_GET['view']) && $_GET['view'] == 'hierarchy') {echo 'current';} ?>">
        <a href="<?php echo html_escape(url('simple-pages/index/browse?view=hierarchy')); ?>"><?php echo __('Hierarchy View'); ?></a>
    </li>
    <li class="<?php if (isset($_GET['view']) && $_GET['view'] == 'hierarchy') {echo 'current';} ?>">
        <a href="<?php echo html_escape(url('simple-pages/categories/index')); ?>"><?php echo __('Categories'); ?></a>
    </li>    
    <li class="current">
        <a href="<?php echo html_escape(url('simple-pages/tags/index')); ?>"><?php echo __('Tags'); ?></a>
    </li>        
</ul>

<?php echo flash(); ?>

<a class="add-page button small green" href="<?php echo html_escape(url('simple-pages/keywords/add')); ?>"><?php echo __('Add a tag'); ?></a>

<?php echo pagination_links(); ?>

<?php if(count($simple_pages_keywords)): ?>
<table class="full">
    <thead>
        <tr>
            <?php echo browse_sort_links(array(
                __('Title') => 'title',
                __('Number of pages') => 'nb_pages',
                __('Last Modified') => 'updated'), array('link_tag' => 'th scope="col"', 'list_tag' => ''));
            ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($simple_pages_keywords as $keyword): ?>
        <tr>
            <td>
                <span class="title">
                    <a target="_blank" href="<?php echo html_escape(record_url($keyword)); ?>"><?php echo $keyword->name; ?></a>
                </span>
                <ul class="action-links group">
                    <li><a href="<?php echo html_escape(url('simple-pages/keywords/edit/id/').$keyword->id); ?>">
                        <?php echo __('Edit'); ?>
                    </a></li>
                    <li><a class="delete-confirm" href="<?php echo html_escape(url('simple-pages/keywords/delete-confirm/id/').$keyword->id); ?>">
                        <?php echo __('Delete'); ?>
                    </a></li>
                </ul>
            </td>
            <td>
                <a target="_blank" href="<?php echo html_escape(record_url($keyword)); ?>"><?php echo $keyword->nb_pages ?></a>
            </td>
            <td><?php echo __('%1$s', html_escape(format_date($keyword->updated, Zend_Date::DATETIME_SHORT))); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>



<?php echo pagination_links(); ?>

<?php fire_plugin_hook('public_simple_page_categories_browse', array('categories'=>$simple_pages_keywords, 'view' => $this)); ?>

<?php echo foot(); ?>
