<?php


class DataModel
{
    /** @PRIMARY_KEY @AUTO_INCREMENT */
    public ?int $id;

    public function __construct(?int $id = null)
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function id(): ?int
    {
        return $this->id;
    }

    public function varAnnotation(string $varName): ?string
    {

        try {

            $r = new ReflectionProperty($this, $varName);
            return is_string($r->getDocComment()) ? $r->getDocComment() : null;

        } catch (Exception $e) {
        }
        return null;
    }


    public function getVarColumn(string $varName): ?string
    {

        $r = new ReflectionProperty($this, $varName);

        if (is_string($annotation = $r->getDocComment()) and strpos($annotation, Annotation::COLUMN))
            return $this->getAnnotationValue($annotation, Annotation::COLUMN);


        return null;
    }

    private function getAnnotationValue(string $annotation, string $annotationName): string
    {
        //space full remove
        $a = str_replace(" ", "", $annotation);
        //find index
        $index = strpos($a, $annotationName) + strlen($annotationName);
        //result
        $val = '';
        for ($i = $index + 1; $i < strlen($a) && $a[$i] != ')'; $i++)
            $val .= $a[$i];


        return $val;
    }

    /**
     * Get variable value as string or int of float or...
     */
    public function getVar(string $varName)
    {
        return get_object_vars($this)[$varName];

    }

    /**
     * Class name of this object
     */
    public function name(): string
    {
        return get_class($this);
    }

    /**
     * Get all object vars as string like "id,name,..."
     */
    public function getAllVars_string(): array
    {
        $res = [];
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value)
            $res[] = "$key";

        return $res;
    }


    /**
     * Get all vars as array
     */
    public function getAllVars(): array
    {
        return get_object_vars($this);
    }

    /**
     * Find Primary Key
     */
    public function getPrimaryKeyName(): ?string
    {

        foreach ($this->getAllVars() as $key => $value) {

            if (strpos($this->varAnnotation($key), Annotation::PRIMARY_KAY))
                return $key;
        }

        return null;
    }

    /**
     * Find Primary Key Value
     */
    public function getPrimaryKeyValue(): ?string
    {

        foreach ($this->getAllVars() as $key => $value) {

            if (strpos($this->varAnnotation($key), Annotation::PRIMARY_KAY))
                return is_string($value) ? "'$value'" : $value;
        }

        return null;
    }


}