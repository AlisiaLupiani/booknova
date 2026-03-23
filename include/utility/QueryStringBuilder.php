<?php


class QueryStringBuilder {

    private string $path_file;
    private const INIT = "?"; 
    private const SEPARATOR = "&";
    private array $params = [];

     public function __construct(string $path_file){
        $this->path_file = $path_file; 
    }

    public function add(string $key, mixed $value){
        $this->params[$key] = $value;
    }

    public function addEncoded(string $key, mixed $value){
        $this->params[$key] = base64_encode($value);
    }

    public function build(): string{
        if(empty($this->params)){
            return $this->path_file;
        }

        return $this->path_file . self::INIT . http_build_query($this->params,'',self::SEPARATOR);
    }

    public function cleanParams(): void{
        $this->params = [];
    }

    public function refresh(string $path_file){
        $this->path_file = $path_file;
        $this->cleanParams();
    }
}


?>