<?php
/**
 * @license  http://www.opensource.org/licenses/mit-license.html MIT License
 * @author   Tim Lytle <tim@timlytle.net>
 */

/**
 * Ridiculously simple view renderer.
 */
class Render
{
    protected $layout;
    protected $key;

    protected $vars = array();

    public function __construct($layout = null, $key = 'content')
    {
        $this->key = $key;
        $this->layout = $layout;
    }

    protected function render($template)
    {
        if(!file_exists($template)){
            throw new RuntimeException('invalid template file: ' . $template);
        }

        ob_start();
        include $template;
        return ob_get_clean();
    }

    function __get($name)
    {
        if(!isset($this->vars[$name])){
            return;
        }

        return $this->vars[$name];
    }

    function __set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    function __invoke($template)
    {
        if($this->layout){
            $this->vars[$this->key] = $this->render($template);
            return $this->render($this->layout);
        }

        return $this->render($template);
    }

    function __call($name, $arguments)
    {
        if(!isset($this->vars[$name])){
            return;
        }

        if(!is_callable($this->vars[$name])){
            return;
        }

        $func = $this->vars[$name];
        return call_user_func_array($func, $arguments);
    }
}
