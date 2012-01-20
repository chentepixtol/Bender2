{% include 'header.tpl' %}
{% set BaseTest = classes.get('BaseTest') %}
{% set OptionTest = classes.get('OptionTest') %}
{% set Option = classes.get('Option') %}
namespace Test\Unit;

{{ BaseTest.printRequire(true) }}
{{ Option.printUse() }}

class Fruit{
    private $id;
    private $name;

    public function __construct($id, $name){
        $this->id = $id;
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }

    public function getId(){
        return $this->id;
    }
}

class {{ OptionTest }} extends {{ BaseTest }}
{

    /**
     * @test
     */
    public function isDefined(){
        $option = new Option(new Fruit(1, 'apple'));
        $this->assertTrue($option->isDefined());

        $optionNull = new Option(null);
        $this->assertFalse($optionNull->isDefined());
    }

    /**
     * @test
     */
    public function isNullTest(){
        $option = new Option(new Fruit(1, 'apple'));
        $this->assertFalse($option->isNull());

        $optionNull = new Option(null);
        $this->assertTrue($optionNull->isNull());
    }


    /**
     * @test
     */
    public function getOrElse()
    {
        $option = new Option(new Fruit(1, 'apple'));

        $result = $option->getOrElse(new Fruit(2, 'banana'));
        $this->assertTrue( $result instanceof Fruit );
        $this->assertEquals($result->getName(), 'apple');

        $optionNull = new Option(null);
        $result = $optionNull->getOrElse(new Fruit(2, 'banana'));
        $this->assertTrue( $result instanceof Fruit );
        $this->assertEquals($result->getName(), 'banana');
    }


    /**
     * @test
     */
    public function getOrNull()
    {
        $option = new Option(new Fruit(1, 'apple'));

        $result = $option->getOrNull();
        $this->assertTrue( $result instanceof Fruit );
        $this->assertEquals($result->getName(), 'apple');

        $optionNull = new Option(null);
        $this->assertNull($optionNull->getOrNull());
    }

    /**
     * @test
     */
    public function onNullAndOnDefined()
    {
        $onNull = function(){
            throw new \InvalidArgumentException("la opcion es nula");
        };

        $option = new Option(new Fruit('1', 'apple'));

        try {
            $option->onNull($onNull);
            $this->assertEquals('apple', $option->onDefined(function(Fruit $fruit){
                return $fruit->getName();
            }));
        } catch (\InvalidArgumentException $e) {
            $this->fail("Lanza la exception");
        }

        $optionNull = new Option(null);

        try {
            $this->assertEquals('banana', $optionNull->onNull(function(){
                return 'banana';
            }));
            $optionNull->onNull($onNull);
            $this->fail("No Lanzo la exception");
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }


    /**
     * @test
     */
    public function map()
    {
        $option = new Option(new Fruit('1', 'apple'));
        $optionNull = new Option(null);

        $onNull = function(){
            return 'apple';
        };
        $onDefined = function(Fruit $fruit){
            return $fruit->getName();
        };

        $this->assertEquals('apple', $option->map($onNull, $onDefined));
        $this->assertEquals('apple', $optionNull->map($onNull, $onDefined));
    }

    /**
     * @test
     */
    public function getOrThrowInDefined()
    {
        $option = new Option(new Fruit('1', 'apple'));
        $fruit = $option->getOrThrow("No existe la Fruta");
        $this->assertEquals('apple', $fruit->getName());
    }

    /**
     * @test
     */
    public function isInstanceOfTest(){
        $option = new Option(new Fruit('1', 'apple'));
        $this->assertTrue($option->isInstanceOf('Test\\Unit\\Fruit'));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function getOrThrowInNull()
    {
        $option = new Option(null);
        $fruit = $option->getOrThrow("No existe la Fruta");
    }

}