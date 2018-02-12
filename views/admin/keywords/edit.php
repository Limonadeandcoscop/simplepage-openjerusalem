<?php
echo js_tag('vendor/tinymce/tinymce.min');
$head = array('bodyclass' => 'simple-pages primary', 
              'title' => __('Categories | Edit "%s"', $category->title));
echo head($head);
?>

<script type="text/javascript">
jQuery(window).load(function() {
    // Initialize and configure TinyMCE.
    tinyMCE.init({
        mode: "none",
        forced_root_block: "",
        theme: "modern",
        menubar:false,
        height:400,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking spellchecker",
            "table contextmenu directionality emoticons paste textcolor fullscreen code responsivefilemanager"
        ],
        relative_urls: false,
        filemanager_title:"Responsive Filemanager",
        external_filemanager_path:"/openjerusalem/omeka/application/views/scripts/javascripts/vendor/filemanager/",
        external_plugins: { "filemanager" : "/openjerusalem/omeka/application/views/scripts/javascripts/vendor/filemanager/plugin.min.js"},
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
        toolbar2: "fullscreen | styleselect responsivefilemanager file image link unlink anchor preview code"            
    });

    tinyMCE.execCommand('mceAddEditor', false, 'simple-pages-text');
});
</script>

<?php echo flash(); ?>
<?php echo $form; ?>
<?php echo foot(); ?>
