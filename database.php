<?php

const FILE_PATH = "users.txt";

interface UserInterface
{
    public function getUser($email);
    public function saveUser($identitier, $name, $email, $password);
}


class TextFile implements UserInterface
{
    private $filePath;

    public function __construct(String $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getUser($email)
    {
        if (file_exists($this->filePath)) {
            $file = fopen($this->filePath, "r");

            while (($line = fgets($file)) !== false) {
                $userInfo = explode(", ", trim($line));
                if ($userInfo[2] === $email) {
                    fclose($file);
                    
                    return [
                        'identifier' => $userInfo[0],
                        'name' => $userInfo[1],
                        'email' => $userInfo[2],
                        'password' => $userInfo[3],
                    ];
                }
            }
            fclose($file);
        }

        return null;
    }

    public function saveUser($identifier, $name, $email, $password):bool
    {
        $line = $identifier . ", " . $name . ", " . $email . ", " . $password . "\n";

        $file = fopen($this->filePath, "a");
        if ($file === false) {
            return false;
        }
        if (fwrite($file, $line) === false) {
            fclose($file);
            return false;
        }

        return true;
    }
}

class UserAgent
{
    private $userData;
    public function __construct(UserInterface $userData)
    {
        $this->userData = $userData;
    }
    public function getUserByEmail($email){
       return $this->userData->getUser($email);
    }

    public function saveUserInfo($identifier, $name, $email, $password){
        if($this->userData->saveUser($identifier, $name, $email, $password)===true){
            return true;
        }
        return false;
    }
}


$userAgent = new UserAgent(new TextFile(FILE_PATH));
