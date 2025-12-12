<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Options
    |--------------------------------------------------------------------------
    |
    | Here you can define the options that are passed to all NovaTinyMCE
    | fields by default.
    |
    */

    'default_options' => [
        'content_css' => '/vendor/tinymce/skins/ui/oxide/content.min.css',
        'skin_url' => '/vendor/tinymce/skins/ui/oxide',
        'content_css_dark' => '/vendor/tinymce/skins/ui/oxide-dark/content.min.css',
        'skin_url_dark' => '/vendor/tinymce/skins/ui/oxide-dark',
        'path_absolute' => '/',
        'plugins' => [
            'lists', 'preview', 'anchor', 'pagebreak', 'image', 'media', 'link', 'wordcount', 'fullscreen', 'directionality', 'code', 'fontsize', 'visualblocks'
        ],
        'selector' => 'textarea',
        // 'forced_root_block' => 'div',
        //'toolbar' => 'undo redo | styleselect | bold italic underline forecolor | fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | image media link',
        'toolbar' => 'undo redo | styleselect | bold italic underline forecolor | fontsize | alignleft aligncenter alignright alignjustify | outdent indent | image media link',
        'menubar' => 'file edit insert view tools',
        // 'menu' => [
        //     //'format' => ['title' => 'Format', 'items' => 'bold italic underline strikethrough superscript subscript codeformat | removeformat']
        // ],
        // 'font_size_formats' => '8pt',
        //'block_formats' => 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5',
        // 'visualblocks_default_state' => true,
        'extended_valid_elements' => 'iframe[src|title|frameborder|allow]',
        'relative_urls' => false,
        'use_lfm' => true,
        'use_dark' => true,
        'lfm_url' => 'filemanager',
    ],
];
