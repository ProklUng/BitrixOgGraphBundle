# Бандл управления OG разметкой для Битрикс + Symfony

## Конфигурация

Файл: local/configs/packages/bitrix_og_graph.yaml

Задаются og метки по умолчанию.

```php
<?php bitrixoggraph:
  enabled: true # Бандл активирован?
  site_name: 'EXAMPLE.RU'
  img: '/favicon.ico'
  fb_admins: '444003346913744'
  article_publisher: 'https://facebook/Example/'
?>
```
## Использование

Фасады для использования в компонентах (component_epilog.php) и статических страницах:

Общее:

  - Запросы кэшируются
  - container() - хэлпер доступа к экземпляру контейнера. Включен в базовый комплект
    конструкции Symfony-Bitrix.

#### Детальная страница

```php
<?php container()->get('bitrix_og_graph_bundle.facade.element')->make(
    $arResult['IBLOCK_ID'],
    $arResult['ID'],
);
?>
```

  og:title берется из ELEMENT_META_TITLE.
  og:description берется из ELEMENT_META_DESCRIPTION.
  og:img берется из PREVIEW_PICTURE. Если preview не существует, то из DETAIL_PICTURE.
  og:timePublished из TIMESTAMP_X
  og:url из DETAIL_PAGE_URL

####  Разводящая страница
```php
<?php container()->get('bitrix_og_graph_bundle.facade.section')->make(
    $arResult['IBLOCK_ID'],
    $arResult['ID'],
);
?>
```

  og:title берется из ELEMENT_META_TITLE.
  og:description берется из ELEMENT_META_DESCRIPTION.
  og:img берется из PICTURE.
  og:timePublished из TIMESTAMP_X
  og:url из SECTION_PAGE_URL

####  Статическая страница

```php
<?php container()->get('bitrix_og_graph_bundle.facade.static')->make(
    $arResult['IBLOCK_ID'],
    $arResult['ID'],
);
?>
```

  og:title берется из свойства страницы title.
  og:description берется из свойства страницы description.
  og:img берется из свойства страницы og:image.
  og:timePublished из времени последнего изменения index.php
  og:url из SECTION_PAGE_URL
