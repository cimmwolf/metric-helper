<?php
namespace DenisBeliaev;

/**
 * Author: Denis Beliaev <cimmwolf@gmail.com>
 *
 * @method static Metric getInstance()
 */


class Metric extends Singleton
{
    private $params;

    /** Adds parameter `name` with `value` if parameter does not exists.
     *
     * If parameter `name` is already exists then throws Exception.
     *
     * @param $name string Name of parameter
     * @param $value mixed Value of parameter
     *
     * @throws \InvalidArgumentException
     */
    public function addParam($name, $value)
    {
        if (isset($this->params[$name]) AND $this->params[$name] != $value)
            throw new \InvalidArgumentException;
        $this->params[$name] = $value;
    }

    /** Deletes `name` parameter
     * @param $name string
     */
    public function deleteParam($name)
    {
        unset($this->params[$name]);
    }

    /** Returns parameters as json string.
     * @return string
     */
    public function getParams()
    {
        return json_encode($this->params, JSON_NUMERIC_CHECK);
    }

    /** Creates Yandex.Metrika code for insertion into HTML
     * @param $id int Yandex.Metrika counter ID
     * @param array $settings
     * @param bool|true $async
     * @param bool|false $xml
     * @return string JavaScript code
     */
    public function yandexMetrika($id, $settings = [], $async = true, $xml = false)
    {
        $settings = array_merge(
            [
                'id' => $id,

                'clickmap' => true,
                'trackLinks' => true,
                'accurateTrackBounce' => true,

                'webvisor' => true,
                'trackHash' => false,
            ],
            $settings
        );

        $settings = array_filter($settings);

        if ($this->params !== null)
            $settings['params'] = $this->params;

        $settings = json_encode($settings, JSON_NUMERIC_CHECK);

        $script = 'try { var yaCounter' . $id . ' = new Ya.Metrika(' . $settings . ');} catch(e) { }';

        if ($async == true) {
            $script = '
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    ' . str_replace('var yaCounter', 'yaCounter', $script) . '
                });
                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks");
            ';
            $code = '<script type="text/javascript">' . $script . '</script>';
        } else {
            $code = '<script src="https://mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>';
            $code .= '<script type="text/javascript">' . $script . '</script>';
        }

        if ($xml == false)
            $code .= '<noscript><div><img src="https://mc.yandex.ru/watch/' . $id . '" style="position:absolute; left:-9999px;" alt="" /></div></noscript>';

        return $code;
    }
}