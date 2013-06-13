<?php
/**
 * Gallery
 *
 * Copyright 2010-2012 by Shaun McCormick <shaun@modx.com>
 *
 * Gallery is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Gallery is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Gallery; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package gallery
 */
/**
 * Default Russian Lexicon Entries for Gallery
 *
 * @package gallery
 * @subpackage lexicon
 */
$_lang['gallery'] = 'Gallery';
$_lang['gallery.active'] = 'Активен';
$_lang['gallery.active_desc'] = 'Если не выбрано,то этот альбом не будет доступен для просмотра.';
$_lang['gallery.album'] = 'Альбом';
$_lang['gallery.album_create'] = 'Создать альбом';
$_lang['gallery.album_err_nf'] = 'Альбом не найден.';
$_lang['gallery.album_err_ns'] = 'Альбом не указан.';
$_lang['gallery.album_err_ns_name'] = 'Введите правильное имя для альбома.';
$_lang['gallery.album_err_remove'] = 'Произошла ошибка при попытке удалить альбом.';
$_lang['gallery.album_err_save'] = 'Произошла ошибка при попытке сохранить галерею.';
$_lang['gallery.album_remove'] = 'Удалить альбом';
$_lang['gallery.album_remove_confirm'] = 'Вы уверенны, что хотите удалить этот альбом? Будут удалены все изображения которые не используются в других альбомах.';
$_lang['gallery.album_update'] = 'Редактировать альбом';
$_lang['gallery.albums'] = 'Альбомы';
$_lang['gallery.back'] = 'Назад';
$_lang['gallery.batch_upload'] = 'Пакетная загрузка';
$_lang['gallery.batch_upload_intro'] = '<p>Укажите каталог для поиска элементов. Вы можете использовать {base_path}, {core_path}, или {assets_path} как подстановщики.</p>';
$_lang['gallery.batch_upload_tags'] = 'Метки которые будут назначенны всем элементам при пакетной загрузке, в виде списка разделённого запятыми.';
$_lang['gallery.bytes'] = 'bytes';
$_lang['gallery.comma_separated_list'] = 'Список меток разделённый запятыми.';
$_lang['gallery.cover_filename'] = 'Обложка альбома';
$_lang['gallery.directory'] = 'Каталог';
$_lang['gallery.directory_desc'] = 'Каталог для поиска элементов.';
$_lang['gallery.directory_err_nf'] = 'Каталог не найден.';
$_lang['gallery.directory_err_ns'] = 'Каталог не указан.';
$_lang['gallery.file'] = 'Файл';
$_lang['gallery.file_name'] = 'Имя файла';
$_lang['gallery.file_size'] = 'Размер файла';
$_lang['gallery.height'] = 'Высота';
$_lang['gallery.images_selected'] = '[[+count]] выбранно.';
$_lang['gallery.inactive'] = 'Неактивен';
$_lang['gallery.intro_msg'] = 'Здесь вы можете управлять альбомами. Щёлкните правой кнопкой мыши на альбом для просмотра дополнительных настроек.';
$_lang['gallery.item_delete'] = 'Удалить елемент';
$_lang['gallery.item_delete_confirm'] = 'Вы уверены, что хотите удалить этот элемент? Это необратимо.';
$_lang['gallery.item_delete_multiple'] = 'Удалить выбранные элементы';
$_lang['gallery.item_delete_multiple_confirm'] = 'Вы уверены, что хотите удалить эти элементы полностью? Это необратимо.';
$_lang['gallery.item_err_nf'] = 'Элемент не найден.';
$_lang['gallery.item_err_ns'] = 'Элемент не указан.';
$_lang['gallery.item_err_ns_file'] = 'Укажите файл для загрузки.';
$_lang['gallery.item_err_remove'] = 'Произошла ошибка при попытке удалить элемент.';
$_lang['gallery.item_err_save'] = 'Произошла ошибка при попытке сохранить элемент.';
$_lang['gallery.item_err_upload'] = 'Произошла ошибка при попытке загрузить элемент.';
$_lang['gallery.item_remove'] = 'Удалить элемент';
$_lang['gallery.item_remove_album'] = 'Удалить элемент из альбома';
$_lang['gallery.item_update'] = 'Редактировать элемент';
$_lang['gallery.item_upload'] = 'Загрузить элемент';
$_lang['gallery.items'] = 'элементы';
$_lang['gallery.menu_desc'] = 'Управление альбомами.';
$_lang['gallery.parent'] = 'Родитель';
$_lang['gallery.prominent'] = 'Видимый';
$_lang['gallery.prominent_desc'] = 'Создание невидимого альбома может быть использовано для сокрытия альбома из списка альбомов, для приватных альбомов или для создания альбомов не включённых в список.';
$_lang['gallery.refresh'] = 'Обновить';
$_lang['gallery.tags'] = 'Метки';
$_lang['gallery.title'] = 'Название';
$_lang['gallery.width'] = 'Ширина';
$_lang['gallery.upload_cover'] = 'Загрузить обложку';
$_lang['gallery.view_cover'] = 'Посмотреть';
$_lang['gallery.set_as_cover'] = 'Сделать обложкой альбома';
$_lang['gallery.current_cover'] = 'Текущая обложка альбома';
$_lang['gallery.cover_upload'] = 'Загрузка обложки';
$_lang['gallery.delete_cover'] = 'Удалить обложку';

$_lang['area_backend'] = 'Настройки бэкенда Gallery';

$_lang['setting_gallery.backend_thumb_far'] = 'Соотношени сторон в бэкенде Gallery';
$_lang['setting_gallery.backend_thumb_far_desc'] = 'Соотношение сторон для миниатюр. Для дополнительной информации смотрите параметр far в документации phpThumb';

$_lang['setting_gallery.backend_thumb_height'] = 'Высота миниатюр в бэкенде Gallery';
$_lang['setting_gallery.backend_thumb_height_desc'] = 'Высота миниатюр в пикселях.';

$_lang['setting_gallery.backend_thumb_width'] = 'Ширина миниатюр в бэкенде Gallery';
$_lang['setting_gallery.backend_thumb_width_desc'] = 'Ширина для миниатюр в пикселях.';

$_lang['setting_gallery.backend_thumb_zoomcrop'] = 'Обрезание миниатюр в бэкенде Gallery';
$_lang['setting_gallery.backend_thumb_zoomcrop_desc'] = 'Включено или нет автоматическое изменения размеров миниатюр.';

$_lang['setting_gallery.default_batch_upload_path'] = 'Путь по умолчанию при пакетной загрузки';
$_lang['setting_gallery.default_batch_upload_path_desc'] = 'Путь по умолчанию для пакетной загрузки.';