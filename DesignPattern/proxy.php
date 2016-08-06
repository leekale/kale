<?php

/**
 * Created by PhpStorm.
 * User: JJBOOM
 * Date: 2016/8/5
 * Time: 19:16
 */

/**
 * 主体接口
 * 客户端只通过这个类来实现
 */
interface Image
{
    public function getWidth();
    public function getHeight();
    public function getPath();

    /**
     * @return string 图片的字节流
     */
    public function dump();
}

/**
 * 抽象类避免代理和主体类重复同样的代码
 * 这里只提供不需要实例化真正主体的方法。
 * （翻译自下面这句话，感觉翻译的不好，大家可以自行体会下）
 * Only the methods which can be provided without instancing
 * the RealSubject are present here.
 */
abstract class AbstractImage implements Image
{
    protected $_width;
    protected $_height;
    protected $_path;
    protected $_data;

    public function getHeight()
    {
        return $this->_height;
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function getPath()
    {
        return $this->_path;
    }
}

/**
 * 真正的主体，总会加载图片_data，即使不使用dump()方法
 */
class RawImage extends AbstractImage
{
    public function __construct($path)
    {
        $this->_path=$path;
        list($this->_width,$this->_height)=getimagesize($path);
        $this->_data=file_get_contents($path);
    }

    public function dump()
    {
        return $this->_data;
    }
}

/**
 * 代理类，延迟加载图片_data。直到被要求加载时候，才会进行加载
 * 推迟了加载BLOB这类数据的高昂代价。
 */
class ImageProxy extends  AbstractImage
{
    public function __construct($path)
    {
        $this->_path=$path;
        list($this->_width,$this->_height)=getimagesize($path);
    }

    /**
     * 实例化RawImage并调用其方法
     */
    protected function _lazyLoad(){
        if($this->_realImage==null){
            $this->_realImage=new RawImage($this->_path);
        }
    }

    public function dump()
    {
        $this->_lazyLoad();
        return $this->_realImage->dump();
    }
}

/**
 * 客户端类，不使用dump()方法。
 * 通过传入一个代理到此类或者其他客户端就可以使用了，
 * 当需要加载data时候去调用Image::dump()
 * 这句话也不是很好翻译，原文为：
 * Passing blindly a Proxy to this class and to other Clients makes sense
 * as the data would be loaded anyway when Image::dump() is called.
 */
class client
{
    public function tag(Image $img){
        return '<img src="' . $img->getPath() . '" alt="" width="'
        . $img->getWidth() . '" height="'
        . $img->getHeight() . '" />';
    }
}

$path='pentakill.jpg';
$client=new client();
echo memory_get_usage();//244048
echo '<hr/>';

$proxy=new ImageProxy($path);//并没有加载BLOB文件
echo $client->tag($proxy);
echo memory_get_usage();//244600
echo '<hr/>';

$image=new RawImage($path);//加载BLOB文件
echo $client->tag($image);
echo memory_get_usage();//752592

























