<?php

/**
 * Created by PhpStorm.
 * User: JJBOOM
 * Date: 2016/8/6
 * Time: 18:02
 */
abstract class AbstractCar{
    public abstract function getPrice();
    public abstract function getManufacturer();
}

class Car extends AbstractCar{
    private  $_price=160000;
    private  $_manufacturer='Acme Autos';
    public function getPrice()
    {
        return $this->_price;
    }

    public function getManufacturer()
    {
        return $this->_manufacturer;
    }
}

/**
 * 将所有的请求转发给目标Car对象，
 * Car和Decorator都是对抽象类的继承，
 * 使得Decorator避免因继承完整的Car而产生多余的系统开销，却仍然能保持多态
 */
class CarDecorator extends AbstractCar{
    private $_target;

    function __construct(Car $target)
    {
        $this->_target=$target;
    }

    public function getPrice()
    {
        return $this->_target->getPrice();
    }

    public function getManufacturer()
    {
        return $this->_target->getManufacturer();
    }

    public function hasDecoratorNamed($name){
        if(get_class($this) ==$name){
            return true;
        }elseif($this->_target instanceof CarDecorator){
            return $this->target->hasDecoratorNamed( $name );
        }else{
            return false;
        }
    }
}


class NavigationSystem extends  CarDecorator{
    public function getPrice()
    {
        return parent::getPrice()+1000;
    }
}

$car=new Car();
echo $car->getPrice();
echo "<hr/>";
$car=new NavigationSystem($car);
echo $car->getPrice();


















