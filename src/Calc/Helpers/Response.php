<?php namespace Calc\Helpers;

use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\Contracts\JsonableInterface;
use Illuminate\Support\MessageBag;
use JsonSerializable;

class Response implements JsonSerializable, ArrayableInterface, JsonableInterface
{
    protected $error;
    protected $message;
    protected $data;

    function __construct($message = '', $error = false, array $data = [])
    {
        $this->data = $data;
        $this->error = $error;
        $this->message = $message;
    }

    /**
     * @param string $message
     *
     * @return self
     */
    public function error($message = null)
    {
        $this->error = true;

        return is_null($message) ? $this : $this->message($message);
    }

    /**
     * @param string $message
     *
     * @return self
     */
    public function message($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param array $data
     * @param bool $merge
     *
     * @return self
     */
    public function data(array $data, $merge = false)
    {
        if ($merge)
        {
            $this->data = array_merge($this->data, $data);
        }
        else
        {
            $this->data += $data;
        }

        return $this;
    }

    /**
     * Set response errors
     *
     * @param array|MessageBag $errors
     *
     * @return self
     */
    public function errors($errors)
    {
        $this->success = false;
        $this->data['errors'] = $errors instanceof MessageBag
            ? $errors->toArray()
            : $errors;

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'success' => ! $this->error,
            'error'   => $this->error,
            'message' => $this->message
        ] + $this->data;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson($options = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this, $options);
    }
}
