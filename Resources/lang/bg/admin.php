<?php

return [
    'catalog_image' => 'Каталог',
    'common'        => [
        'invalid_server_requirements' => '<strong>Изискванията към сървъра не са изпълнени.</strong> Моля, обърнете се към администратор.',
        'upload_max_filesize'         => 'upload_max_filesize е твърде малък. Минимум 200M трябва да бъде настроен в php.ini',
        'post_max_size'               => 'post_max_size е твърде малък. Минимум 200M трябва да бъде настроен в php.ini',
        'view_catalog'                => 'Виж каталога',
        'download_catalog'            => 'Свали каталога',
    ],
    'catalogs_main' => [
        'index'                       => 'Каталози',
        'create'                      => 'Добавяне на каталог',
        'edit'                        => 'Редактиране на каталог',
        'catalog_file'                => 'Файл на каталога',
        'catalog_file_info'           => 'Каталогът трябва да е с разширение <strong>.pdf</strong> и с максимален размер <strong>200 МВ</strong>',
        'catalog_thumbnail_file'      => 'Файл за миниатюра на каталога',
        'catalog_thumbnail_file_info' => 'Миниатюратa на каталога трябва да е <strong>.png</strong> и с максимален размер <strong>3 МВ</strong>',
    ],
    'catalogs'      => [
        'index'                                         => 'Страници с каталози',
        'create'                                        => 'Добавяне на каталог към страница',
        'edit'                                          => 'Редактиране на каталог',
        'first_choose_from_list'                        => '<strong>Внимание!</strong> За да добавите или разгледате каталог е необходимо да изберете страница от списъка.',
        'warning_no_catalogs_methods_in_eloquent_model' => '<strong>Внимание!</strong> Търсеният от Вас модул няма асоциирани страници с каталози.',
        'after_main_description'                        => 'Каталози след основен текст',
        'after_additional_description_1'                => 'Каталози след допълнителен текст 1',
        'after_additional_description_2'                => 'Каталози след допълнителен текст 2',
        'after_additional_description_3'                => 'Каталози след допълнителен текст 3',
        'after_additional_description_4'                => 'Каталози след допълнителен текст 4',
        'after_additional_description_5'                => 'Каталози след допълнителен текст 5',
        'after_additional_description_6'                => 'Каталози след допълнителен текст 6',
        'select_main_catalog'                           => 'Основен каталог',
        'warning_class_not_found'                       => '<strong>Внимание!</strong> Възникна грешка.',
    ],
    'editor_import' => [
        'import_in_editor' => 'Вмъкване на каталог в едитора',
        'catalog'          => 'Каталог',
        'choose_catalog'   => 'избери каталог',
        'import_chosen_catalog' => 'вмъкни каталог',
        'step_1' => '1. Изберете каталог от падащото меню',
        'step_2' => '2. Кликнете в едитора, където искате да се покаже файла',
        'step_3' => '3. Натиснете бутонът "Вмъкни каталог"',
        'no_catalogs_added' => 'Няма добавени каталози. Моля, първо дабавете каталог, за да може да го дабавите тук.'
    ],
];
