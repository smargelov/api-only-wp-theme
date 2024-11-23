<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', 'crb_attach_theme_options');
function crb_attach_theme_options() {
    /*
     * Блок дополнительных настроек для стандартных постов
     */
    Container::make('post_meta', __('Настройки постов'))
        /* Применяем к типу постов - "post" */
        ->where('post_type', '=', 'post')
        /* Добавляем поля для категории "markup" */
        ->where('post_term', '=', array(
            'field' => 'slug',
            'value' => 'markup',
            'taxonomy' => 'category',
        ))
        ->set_context('carbon_fields_after_title')
        ->add_tab(__('Основная информация'), array(
            /* Простое текстовое поле */
            Field::make('text', 'crb_post_subtitle', 'Подзаголовок')
                ->set_help_text('Введите подзаголовок для поста.')
                ->set_visible_in_rest_api(true),

            /* Поле для добавления большого текста с WYSIWYG редактором */
            Field::make('rich_text', 'crb_post_description', 'Описание')
                ->set_help_text('Полное описание поста.')
                ->set_visible_in_rest_api(true),
        ))
        ->add_tab(__('Дополнительные настройки'), array(
            /* Поле для добавления числа (числовое поле заменено на text с атрибутами) */
            Field::make('text', 'crb_post_views', 'Количество просмотров')
                ->set_default_value(0)
                ->set_attribute('type', 'number')
                ->set_attribute('min', 0)
                ->set_visible_in_rest_api(true),

            /* Выпадающий список */
            Field::make('select', 'crb_post_priority', 'Приоритет поста')
                ->add_options(array(
                    'low' => 'Низкий',
                    'medium' => 'Средний',
                    'high' => 'Высокий'
                ))
                ->set_help_text('Укажите приоритет отображения поста.')
                ->set_visible_in_rest_api(true),

            /* Цветовое поле */
            Field::make('color', 'crb_highlight_color', 'Цвет выделения')
                ->set_palette(['#FFFFFF', '#FF0000', '#00FF00', '#0000FF'])
                ->set_help_text('Выберите цвет выделения для поста.')
                ->set_visible_in_rest_api(true),

            /* Поле "дата и время" */
            Field::make('date_time', 'crb_publication_date', 'Дата публикации')
                ->set_storage_format('Y-m-d H:i:s')
                ->set_visible_in_rest_api(true)
                ->set_visible_in_rest_api(true),
        ))
        ->add_tab(__('Раздел FAQ'), array(
            /* Группа полей */
            Field::make('complex', 'crb_faq_section', 'Раздел FAQ')
                ->add_fields(array(
                    Field::make('text', 'faq_question', 'Вопрос')
                        ->set_width(50),
                    Field::make('rich_text', 'faq_answer', 'Ответ')
                        ->set_width(50),
                ))
                ->setup_labels(array(
                    'plural_name'   => 'вопросы',
                    'singular_name' => 'вопрос',
                ))
                ->set_max(5)
                ->set_help_text('Максимум 5 вопросов.')
                ->set_visible_in_rest_api(true),
        ))
        ->add_tab(__('Медиа и ссылки'), array(
            /* Поле для добавления ссылки (заменено на текстовое поле с проверкой URL) */
            Field::make('text', 'crb_external_link', 'Внешняя ссылка')
                ->set_attribute('type', 'url')
                ->set_help_text('Укажите ссылку на внешний ресурс.')
                ->set_visible_in_rest_api(true),

            /* Поле для добавления файла */
            Field::make('file', 'crb_attachment_file', 'Файл вложения')
                ->set_value_type('url')
                ->set_help_text('Загрузите файл, который можно прикрепить к посту.')
                ->set_visible_in_rest_api(true),

            /* Поле для многократного выбора (чекбоксы) */
            Field::make('multiselect', 'crb_additional_categories', 'Дополнительные категории')
                ->add_options(array(
                    'category_a' => 'Категория A',
                    'category_b' => 'Категория B',
                    'category_c' => 'Категория C'
                ))
                ->set_visible_in_rest_api(true),

            /* Поле "радио" для выбора одного варианта */
            Field::make('radio', 'crb_content_type', 'Тип контента')
                ->add_options(array(
                    'article' => 'Статья',
                    'video' => 'Видео',
                    'audio' => 'Аудио'
                ))
                ->set_default_value('article')
                ->set_visible_in_rest_api(true),
        ))
        ->add_tab(__('Автор и настройки'), array(
            /* Поле для добавления пользователя заменено на текстовое поле для имени пользователя */
            Field::make('text', 'crb_author_override', 'Автор поста')
                ->set_help_text('Введите имя пользователя, который будет указан в качестве автора вместо основного.')
                ->set_visible_in_rest_api(true),

            /* Поле для добавления координат (карта) */
            Field::make('map', 'crb_event_location', 'Место проведения')
                ->set_help_text('Укажите место проведения мероприятия.')
                ->set_visible_in_rest_api(true),

            /* Переключатель "вкл/выкл" (toggle) */
            Field::make('checkbox', 'crb_show_featured', 'Показать в рекомендованных')
                ->set_default_value(true)
                ->set_visible_in_rest_api(true),

            /* Поле для добавления HTML-контента */
            Field::make('html', 'crb_custom_html', 'Кастомный HTML блок')
                ->set_html('<div style="padding:10px; background:#f5f5f5;">Это пример кастомного HTML контента</div>'),

            /* Поле "ассоциации" для выбора связанного контента */
            Field::make('association', 'crb_related_posts', 'Связанные посты')
                ->set_types(array(
                    array(
                        'type' => 'post',
                        'post_type' => 'post',
                    ),
                ))
                ->set_help_text('Выберите связанные посты для этой публикации.')
                ->set_visible_in_rest_api(true),
        ));

    /*
     * Блок глобальных настроек темы
     */
    Container::make('theme_options', __('Настройки темы'))
        ->set_page_parent('themes.php')
        ->add_tab(__('Основные настройки'), array(
            Field::make('text', 'crb_site_copyright', 'Копирайт сайта')
                ->set_default_value('Все права защищены.'),

            Field::make('image', 'crb_logo', 'Логотип сайта')
                ->set_value_type('url')
                ->set_help_text('Загрузите логотип сайта.'),
        ))
        ->add_tab(__('Социальные сети'), array(
            Field::make('checkbox', 'crb_enable_social_icons', 'Включить социальные иконки')
                ->set_default_value(true),

            Field::make('complex', 'crb_social_links', 'Ссылки на социальные сети')
                ->add_fields(array(
                    Field::make('text', 'platform', 'Название платформы')
                        ->set_width(30),
                    Field::make('text', 'link', 'Ссылка на профиль')
                        ->set_attribute('type', 'url')
                        ->set_width(70),
                ))
                ->set_conditional_logic(array(
                    array(
                        'field' => 'crb_enable_social_icons',
                        'value' => true,
                    ),
                ))
                ->setup_labels(array(
                    'plural_name'   => 'социальные сети',
                    'singular_name' => 'социальная сеть',
                )),
        ));

    /*
     * Блок настроек для категории "project"
     */
    Container::make('term_meta', __('Настройки категории'))
        ->where('term_taxonomy', '=', 'category')
        ->add_fields(array(
            Field::make('text', 'crb_category_custom_field', 'Дополнительное поле категории')
                ->set_help_text('Введите дополнительную информацию для этой категории.'),
        ));

    /*
     * Блок пользовательских настроек профиля
     */
    Container::make('user_meta', __('Настройки профиля'))
        ->add_fields(array(
            Field::make('text', 'crb_user_facebook', 'Facebook профиль')
                ->set_help_text('Введите ссылку на ваш профиль Facebook.'),
            Field::make('image', 'crb_user_avatar', 'Аватар пользователя')
                ->set_value_type('url')
                ->set_help_text('Загрузите аватар для профиля.'),
        ));
}
