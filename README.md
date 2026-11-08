# Medved Beauty Bar

[Публичная версия на GitHub Pages](https://fedinartemii.github.io/MedvedBeautyBar/) · [Репозиторий](https://github.com/FedinArtemii/MedvedBeautyBar)

Лендинг студии красоты в Челябинске. Первый экран соответствует согласованному варианту B «Широкий кадр». Основное действие открывает сообщество студии ВКонтакте.

## Локальный WordPress

1. В Windows PowerShell из папки проекта выполните `./scripts/local.ps1 -Start`. Нужен PHP для Windows с расширениями PDO SQLite, SQLite3, GD и mbstring. При первом запуске скрипт скачает WordPress и SQLite Database Integration.
2. Откройте [сайт](http://127.0.0.1:8079/) или [админку](http://127.0.0.1:8079/wp-admin/). Данные первого входа лежат в `.local/admin.txt`.
3. В разделе «Страницы» откройте «Главная». Заголовки, фотографии, ссылки, описания услуг и адрес редактируются блоками WordPress. Для замены фотографии можно загрузить файл в медиатеку и выбрать его в блоке изображения.

При повторном запуске скрипт использует существующую базу. Начальное содержимое хранится в `theme/medved-beauty-bar/landing.blocks.html` и импортируется при первой установке.

## Обновление публичной копии

Сохраните и опубликуйте изменения в локальном WordPress. Запустите `./scripts/export.ps1` и отправьте изменения папки `docs` в ветку `main`. GitHub Pages публикует папку `/docs`. Экспорт забирает опубликованную главную страницу и её локальные фотографии. Стили, шрифты и изображения лежат отдельно от HTML.

## Движение

На входе типографика и фотография мягко занимают свои места. В блоке адреса кадр маникюра один раз раскрывается по дуге, которая повторяет круг логотипа. При уменьшенном движении фотография сразу показана целиком, а меню и описания услуг открываются без анимации.

## Материалы

- Логотип из брифа.
- Фотографии служат иллюстрациями услуг. [Маникюр с цветком](https://www.pexels.com/photo/pink-flower-on-hand-with-polished-nails-20758448/), [маникюр на розовом фоне](https://www.pexels.com/photo/hands-with-manicured-nails-9775261/), [оформление бровей](https://www.pexels.com/photo/mascara-applicator-3762664/), [процесс маникюра](https://www.pexels.com/photo/close-up-of-woman-having-manicure-332046/). [Лицензия Pexels](https://www.pexels.com/license/).
- Адрес и ВКонтакте из [карточки студии](https://yandex.ru/medicine/clinic/medved-byuti-bar_210274487347). Услуги из [каталога](https://yandex.com/maps/org/medved_beauty_bar/210274487347/prices/) и [описания студии](https://dreamjob.ru/employers/950911/vakansii/125594123).
- Шрифты Cormorant Garamond и Manrope поставляются с лицензиями SIL Open Font License в папке со шрифтами.
