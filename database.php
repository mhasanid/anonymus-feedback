<?php

const FILE_PATH_USER = "users.txt";
const FILE_PATH_FEEDBACK = "feedback.txt";

interface UserInterface
{
    public function getUserByMail($email);
    public function getUserById($email);
    public function saveUser($identitier, $name, $email, $password);
}

interface FeedBackInterface
{
    public function getAllFeedback($identifier):array;
    public function saveFeedback($identifier, $feedback):bool;
}


class TextFileUser implements UserInterface
{
    private $filePath;

    public function __construct(String $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getUserByMail($email)
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

    public function getUserById($identifier)
    {
        if (file_exists($this->filePath)) {
            $file = fopen($this->filePath, "r");

            while (($line = fgets($file)) !== false) {
                $userInfo = explode(", ", trim($line));
                if ($userInfo[0] === $identifier) {
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

class TextfileFeedback implements FeedBackInterface
{
    private $filePath;

    public function __construct(String $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getAllFeedback($identifier): array
    {
        if (file_exists($this->filePath)) {
            $serializedArray = file_get_contents($this->filePath);
            $array = unserialize($serializedArray)?unserialize($serializedArray):[];;
            if(empty($array) || !array_key_exists($identifier, $array)){
                return [];
            }
            return $array[$identifier];
        } else {
            return [];
        }
    }

    public function saveFeedback($identifier, $feedback):bool
    {
        if (file_exists($this->filePath)) {
            $serializedArray = file_get_contents($this->filePath);
            $array = unserialize($serializedArray)?unserialize($serializedArray):[];
        } else {
            $array = [];
        }

        if (array_key_exists($identifier, $array)) {
            $array[$identifier][] = $feedback;
        } else {
            $array[$identifier] = [$feedback];
        }

        $serializedArray = serialize($array);

        if(file_put_contents($this->filePath, $serializedArray)){
            return true;
        }
        return false;
        
    }
}

class FeedbackManager
{
    private $textfileFeedback;
    public function __construct(FeedBackInterface $textfileFeedback)
    {
        $this->textfileFeedback=$textfileFeedback;
    }
    public function getAllFeedbackById($identifier){
        return $this->textfileFeedback->getAllFeedback($identifier);
    }
    public function saveFeedback($identifier, $feedback){
        return $this->textfileFeedback->saveFeedback($identifier, $feedback);
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
       return $this->userData->getUserByMail($email);
    }

    public function getUserById($identifier){
        return $this->userData->getUserById($identifier);
     }

    public function saveUserInfo($identifier, $name, $email, $password){
        if($this->userData->saveUser($identifier, $name, $email, $password)===true){
            return true;
        }
        return false;
    }
}


$userAgent = new UserAgent(new TextFileUser(FILE_PATH_USER));
$feedbackManager = new FeedbackManager(new TextfileFeedback(FILE_PATH_FEEDBACK));
