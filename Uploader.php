<?php

namespace Core\Security;

use Exception;

class Uploader
{
    private String $name;
    private String $type;
    private String $tmp_name;
    private Bool $error;
    private Int $size;
    private String $path;
    private String $extension;
    
    /**
     * __construct
     * Define object properties by file given and its path
     *
     * @param  array $file
     * @param  string $path
     * @return void
     */
    public function __construct(Array $file, String $path)
    {
        $name = $file['name'];
        $type = $file['type'];
        $tmp_name = $file['tmp_name'];
        $error = $file['error'];
        $size = $file['size'];

        $this->name = $name;
        $this->type = $type;
        $this->tmp_name = $tmp_name;
        $this->error = $error;
        $this->size = $size;
        $this->path = $path;
        $this->extension = pathinfo($name)['extension'];
    }
    
    /**
     * checkMaxSize
     * Check if the file don't exceed the $max_size
     * @param  int $max_size
     * @throws Exception
     * @return bool
     */
    public function checkMaxSize(Int $max_size): void
    {
        $size = $this->size;
        if ($size > $max_size) {
            throw new Exception("The file is too big : $max_size octets authorized");
        }
    }
    
    /**
     * check_extension
     * Return True if the file have an authorized extension (defined by $authorized_extensions)
     * @param  array $authorized_extensions
     * @throws Exception
     * @return bool
     */
    public function check_extension(array $authorized_extensions): void
    {
        if (!in_array($this->extension, $authorized_extensions)) {
            throw new Exception("Extension not authorized.");
        }
    }
        
    /**
     * check_empty
     * Check if the file is empty
     * @throws Exception
     * @return bool
     */
    public function check_empty(): void
    {
        
        if ($this->size <= 0) {
                throw new Exception("The file is empty");
        }
    }    
    /**
     * rename_file
     * Rename the file with a random string chain (10 characters by default.)
     * Or with a name given.
     * Ex: rename_file("toto") => toto.jpg
     * Ex: rename_file(null, 5) => aekqI.jpg
     * @param  string $name
     * @param  int $nb_char
     * @return string
     */
    public function rename_file(?String $name = null, Int $nb_char = 10): String
    {
        
        if (!is_null($name)) {
            $this->name = $name;
        }else{
            $this->name = $this->generate_name($nb_char);
        }

        return $this->name;
    }
    
    /**
     * move_file
     * Try to move the final file from /tmp to the path given with name and extension.
     * Ex : /tmp/mytempfile.jpg => /my/path/myfile.jpg
     * @throws Exeption
     * @return bool
     */
    public function move_file(): void
    {
        $tmp_name = $this->tmp_name;
        $path = $this->path;
        $name = $this->name;
        $extension = $this->extension;
        if(!move_uploaded_file($tmp_name, $path.$name.'.'.$extension)){
            throw new Exception("Error failed to move $tmp_name to $path $name.$extension");
        }
    }

    /**
     * generate_name
     * Generate a random string
     * @param  int $nb_char
     * @return string
     */
    private function generate_name(int $nb_char): string
    {
        $string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-";
        $charactersLength = strlen($string);
        $randomString = '';

        for ($i = 0; $i < $nb_char; $i++) {
            $randomString .= $string[rand(0, $charactersLength - 1)];
        }
        $name = $randomString;

        return $name;
    }
}