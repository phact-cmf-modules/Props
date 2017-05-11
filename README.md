# Пользовательские свойства

## Установка

В описании модулей указываем модель, к которой будем подключать свойства:

```php
[
...
    'Props' => [
        'modelClass' => \Modules\Props\Models\Product::class
    ]
...
]
```

В указываемой модели необходимо добавить поле *raw_props* и перекрыть менеджер.
Пример есть в модели *\Modules\Props\Models\Product*

## Заполнение из админ-панели

Пример есть в форме *\Modules\Props\Models\ProductForm::class*

## Заполнение из кода

См: *\Modules\Props\Helpers\PropHelper::setValues*

## Фильтрация/сортировка по полю:

См: *\Modules\Props\Controllers\PropController*