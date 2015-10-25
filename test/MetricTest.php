<?php

/**
 * Author: Denis Beliaev <cimmwolf@gmail.com>
 */
use DenisBeliaev\Metric;

class MetricTest extends PHPUnit_Framework_TestCase
{
    private $yaCounterId = '123098';

    /**
     * @return Metric
     */
    public function testAddParam()
    {
        $Metric = Metric::getInstance();

        $Metric->addParam('param1', 'value1');
        $this->assertContains('{"param1":"value1"}', $Metric->yandexMetrika($this->yaCounterId));

        $Metric->addParam('param2', 'value2');
        $this->assertContains('{"param1":"value1","param2":"value2"}', $Metric->yandexMetrika($this->yaCounterId));

        $Metric->addParam('param3', 100);
        $this->assertContains('{"param1":"value1","param2":"value2","param3":100}', $Metric->yandexMetrika($this->yaCounterId));

        return $Metric;
    }

    /**
     * @depends testAddParam
     * @param Metric $Metric
     * @return Metric
     */
    public function testDeleteParam($Metric)
    {
        $Metric->deleteParam('param1');
        $this->assertNotContains('{"param2":"value2"}', $Metric->yandexMetrika($this->yaCounterId));
        return $Metric;
    }

    /**
     * @depends testAddParam
     * @expectedException InvalidArgumentException
     * @param Metric $Metric
     * @return Metric
     */
    public function testAddExistParam($Metric)
    {
        $Metric->addParam('param2', 'value3');
        return $Metric;
    }

    /**
     * @depends testDeleteParam
     * @param Metric $Metric
     */
    public function testYaCode($Metric)
    {
        $code = $Metric->yandexMetrika($this->yaCounterId);
        $this->assertContains('yaCounter' . $this->yaCounterId, $code);
        $this->assertContains('"id":' . $this->yaCounterId, $code);
        $this->assertContains('mc.yandex.ru/watch/' . $this->yaCounterId, $code);
        $this->assertNotContains('var yaCounter', $code);

        $code = $Metric->yandexMetrika($this->yaCounterId, [], false, true);
        $this->assertContains('src="https://mc.yandex.ru/metrika/watch.js"', $code);
        $this->assertNotContains('s.async', $code);
        $this->assertNotContains('noscript', $code);
    }
}