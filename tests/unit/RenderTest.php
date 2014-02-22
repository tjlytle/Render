<?php
/**
 * @author Tim Lytle <tim@timlytle.net>
 */

class RenderTest extends PHPUnit_Framework_TestCase
{
    protected $tmpFiles = array();

    public function testLayoutMissingException()
    {
        $this->setExpectedException('RuntimeException');
        $render = new Render(__DIR__ . '/bad/file');
        $render($this->fakeFile('fake template'));
    }

    public function testTemplateMissingException()
    {
        $this->setExpectedException('RuntimeException');
        $render = new Render($this->fakeFile('fake layout'));
        $render(__DIR__ . '/bad/file');

    }

    public function testRendersTemplate()
    {
        $render = new Render();
        $output = $render($this->fakeFile('test template'));
        $this->assertEquals('test template', $output);
    }

    public function testRendersLayout()
    {
        $render = new Render($this->fakeFile('test layout'));
        $output = $render($this->fakeFile(''));
        $this->assertEquals('test layout', $output);
    }

    public function testDefaultContentKey()
    {
        $render = new Render($this->fakeFile('test layout <?php echo $this->content ?>'));
        $output = $render($this->fakeFile('test template'));
        $this->assertEquals('test layout test template', $output);
    }

    public function testCustomContentKey()
    {
        $render = new Render($this->fakeFile('test layout <?php echo $this->custom ?>'), 'custom');
        $output = $render($this->fakeFile('test template'));
        $this->assertEquals('test layout test template', $output);
    }


    public function testVariablesAsProperties()
    {
        $render = new Render();
        $render->test = 'value';
        $this->assertEquals('value', $render($this->fakeFile('<?php echo $this->test ?>')));
    }

    public function testHelpersAsMethods()
    {
        $render = new Render();
        $render->helper = function($value){
            return str_rot13($value);
        };
        $this->assertEquals(str_rot13('value'), $render($this->fakeFile('<?php echo $this->helper("value") ?>')));
    }

    protected function fakeFile($content)
    {
        $file = sys_get_temp_dir() . '/' . uniqid('rendertest');
        file_put_contents($file, $content);
        $this->tmpFiles[] = $file;
        return $file;
    }

    public function tearDown()
    {
        foreach($this->tmpFiles as $file){
            unlink($file);
        }
    }
}
 