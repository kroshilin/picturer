<?php
/**
 * Created by PhpStorm.
 * User: kroshilin
 * Date: 2019-05-25
 * Time: 15:59
 */

namespace App\Resolver;


use App\Controller\PictureController;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ControllerFactory implements \Iterator, \ArrayAccess
{
    /** @var ContainerInterface */
    private $container;

    protected $map = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->map);
    }

    protected function resolveController($class, $method)
    {
        return function ($value, Argument $args, \ArrayObject $context, ResolveInfo $info) use ($class, $method) {
            $reflectedMethod = new \ReflectionMethod($class, $method);
            $argsToCall = [];

            if ($args['input'] ?? false) {
                foreach ($reflectedMethod->getParameters() as $arg) {
                    $reflectedInputClass = new \ReflectionClass($arg->getType()->getName());
                    $params = $reflectedInputClass->getMethod("__construct")->getParameters();
                    $createInputWithArgs = [];
                    foreach ($params as $param) {
                        if (isset($args['input'][$param->getName()])) {
                            $createInputWithArgs[] = $args['input'][$param->getName()];
                        }
                    }
                    $args[$arg->name] = $reflectedInputClass->newInstanceArgs($createInputWithArgs);
                }
            }

            foreach ($reflectedMethod->getParameters() as $arg) {
                /** @var \ReflectionParameter $arg */
                if ($args[$arg->name] ?? false) {
                    $argsToCall[$arg->name] = $args[$arg->name];
                }
                if ($arg->getType()->getName() === Argument::class) {
                    $argsToCall['args'] = $args;
                }
            }

            return $reflectedMethod->invokeArgs($this->container->get($class), $argsToCall);
        };
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->map);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        if ($this->map[$offset] ?? false) {
            return $this->resolveController($this->map[$offset][0], $this->map[$offset][1]);
        }
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    function rewind() {
        return reset($this->map);
    }
    function current() {
        return current($this->map);
    }
    function key() {
        return key($this->map);
    }
    function next() {
        return next($this->map);
    }
    function valid() {
        return key($this->map) !== null;
    }
}