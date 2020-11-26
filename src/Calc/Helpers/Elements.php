<?php namespace Calc\Helpers;

use Calc\Model\AdditionalCoefficient;
use Calc\Model\Coefficient;
use Calc\Model\ConstructorRate;
use Calc\Model\Variable;

class Elements implements \JsonSerializable
{
    protected $variables = [];
    protected $rates = [];
    protected $coefficients = [];
    protected $additionalCoefficients = [];

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return array
     */
    public function all()
    {
        return [
            'variables'               => $this->variables(),
            'rates'                   => $this->rates(),
            'coefficients'            => $this->coefficients(),
            'additional_coefficients' => $this->additionalCoefficients()
        ];
    }

    /**
     * @return array
     */
    public function variables()
    {
        if ($this->variables) return $this->variables;

        foreach (Variable::all() as $v)
        {
            $this->variables[$v->name] = $v;
        }

        return $this->variables;
    }

    /**
     * @return array
     */
    public function additionalCoefficients()
    {
        if ($this->additionalCoefficients) return $this->additionalCoefficients;

        $this->additionalCoefficients = AdditionalCoefficient::all()->toArray();

        return $this->additionalCoefficients;
    }

    /**
     * @return array
     */
    public function coefficients()
    {
        if ($this->coefficients) return $this->coefficients;

        $this->coefficients = Coefficient::all()->toArray();

        return $this->coefficients;
    }

    /**
     * @return array
     */
    public function rates()
    {
        if ($this->rates) return $this->rates;

        $this->rates = ConstructorRate::all()->toArray();

        return $this->rates;
    }

    /**
     * @return array|mixed
     */
    function jsonSerialize()
    {
        return $this->all();
    }

    function __toString()
    {
        return $this->toJson();
    }
}
