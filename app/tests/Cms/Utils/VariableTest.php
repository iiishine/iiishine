<?php

use Bigecko\Larapp\Cms\Utils\Variable;

class VariableTest extends TestCase
{
    public function testGet()
    {
        \DB::table('variables')->insert(array(
            'name' => 'sitename',
            'value' => 'mysite',
        ));
        $v = $this->createObj();
        $this->assertEquals('mysite', $v->get('sitename'));
    }

    public function testHas()
    {
        \DB::table('variables')->insert(array(
            'name' => 'sitename',
            'value' => 'mysite',
        ));

        $v = $this->createObj();
        $this->assertFalse($v->has('nonename'));
        $this->assertTrue($v->has('sitename'));
    }

    public function testSet()
    {
        $v = $this->createObj();
        $v->set('myvar', 'harry');
        $this->assertEquals('harry', $v->get('myvar'));
    }

    public function testGetDefault()
    {
        $v = $this->createObj();
        $this->assertEquals('defaultvalue', $v->get('noexist', 'defaultvalue'));
    }

    protected function createObj()
    {
        return new Variable(App::make('db'));
    }
}