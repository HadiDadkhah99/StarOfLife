<?php


class RandomHash
{

    /** @var $type string */
    private $type;
    /** @var $length int */
    private $length;
    /** @var $band string */
    private $band;


    /**
     * RandomHash constructor
     * @param string $type
     * @param int $length
     * Default type is : A-Z,a-z,0-9
     * or you can set custom type like:
     *
     * $type=a-z,0-9
     * or
     * $type=A-Z,a-z
     * or
     * $type=0-9
     * or
     * .
     * .
     * .
     */
    public function __construct(string $type = '', int $length = 32)
    {
        $this->type = $type;
        $this->length = $length;
    }



    /**
     * @return string
     * @throws Exception
     */
    public function randomHash(): string
    {
        //result hash
        $res = "";

        //band
        $band = empty($this->band) ? $this->getBand() : $this->band;


        try {

            //create random hash
            for ($i = 0; $i < $this->length; $i++)
                $res .= $band[random_int(0, strlen($band) - 1)];

        } catch (Exception $e) {
            //do nothing
        }

        return $res;

    }

    /** @return string
     * @throws Exception
     */
    private function getBand(): string
    {
        //result
        $res = "";

        //if type is not set show generate default hash
        if (empty($this->type))
            $res = $this->getInstructionBand('A', 'Z') . $this->getInstructionBand('a', 'z') . $this->getInstructionBand('0', '9');

        //if type is set
        else {

            //full trim
            $type = str_replace(' ', '', $this->type);

            //explode (,)
            $instructions = explode(',', $type);

            //get all instructs
            foreach ($instructions as $value) {

                //dash data
                $dash = explode('-', $value);

                //check dash length
                if (empty($dash) || count($dash) != 2)
                    throw new Exception("Random Hash: Err in parse $value");

                //add band data
                $res .= $this->getInstructionBand($dash[0], $dash[1]);

            }//end for

        }//end else

        return $res;
    }

    /**
     * @throws Exception
     */
    private function getInstructionBand($start, $end): string
    {
        //result
        $res = "";

        //check length
        if (strlen($start) != 1 || strlen($end) != 1)
            throw new Exception("Random hash: err in parse data !");

        //start ascii code
        $s = ord($start);
        //end ascii code
        $e = ord($end);

        for ($i = $s; $i <= $e; $i++)
            $res .= chr($i);


        return $res;
    }

    /**
     * @param string $band
     * Set custom band like: 0123456789
     * This band generate a random hash that contains: 0123456789
     * @throws Exception
     */
    public function setBand(string $band)
    {
        if (!empty($this->type))
            throw  new Exception("Random hash: don't use custom band and type together, please clear type or custom band !");

        $this->band = $band;
    }


}