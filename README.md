# Metric Class
`[eng]`

This class helps to link PHP parameters and JavaScript metric in one system without global JS variables.

Now it supports Yandex.Metrika counter only.

### Methods
   * addParam(name, value) — add parameter `name` with `value` if parameters does not exists;
   * deleteParam(name) — delete parameter `name`;
   * yandexMetrika(id, settings, async, xml) — return js metric code with set parameters.


`[рус]`

Этот класс помогает передавать параметры визита от PHP к JavaScript счётчику посещений без использования глобальных переменных.

Сейчас поддерживается только счётчик Яндекс.Метрики.