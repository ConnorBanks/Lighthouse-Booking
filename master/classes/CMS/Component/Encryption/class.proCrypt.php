<?php
//error_reporting(E_ALL);
// *** CRYPT CLASS *** \\
class proCrypt 
{
    private $cipher = 'AES-128-CBC';
    private $hash_hmac_type = 'sha256';

    function __construct()
    {
        $EN_KEY_1 = '605RlxP/7bD95eCpYw74HU4tmRP2fpJNaT&16Dm0hhNl%8kGLYMo99wpx8&5A8A3';
        $EN_KEY_2 = 'K9b9+y*R3yf7H8iL3AQRE7I%qsLi7U18pICd4g518KhQmTf2g3QUvlbIX44f6w7#';
        $string1 = substr($EN_KEY_1, 0, 32);
        $string2 = substr($EN_KEY_2, 32, 32);
        $this->key_1 = $string1.$string2;
        
        $string1 = substr($EN_KEY_1, 32, 32);
        $string2 = substr($EN_KEY_2, 0, 32);
        $this->key_2 = $string1.$string2;

        $this->ivlen = openssl_cipher_iv_length($this->cipher);
    }

    public function __get( $name )
    {
        switch($name)
        {
            case 'iv':
                return openssl_random_pseudo_bytes($this->ivlen);

            default:
                throw new Exception( "$name cannot be called" );
        }
    }

    /**
    *
    * Encrypt a string
    *
    */
    public function encrypt( $text )
    {
        if ($text == '')
        {
            return '';
        }
        else
        {        
            // add end of text delimiter
            $iv = $this->iv;
            $ciphertext_raw = openssl_encrypt($text, $this->cipher, $this->key_2, $options=OPENSSL_RAW_DATA, $iv);
            $hmac = hash_hmac($this->hash_hmac_type, $ciphertext_raw, strrev($this->key_1), $as_binary=true);
            
            $ciphertext = $iv.$hmac.$ciphertext_raw;
            
            return base64_encode( $ciphertext );
        }
    }

    /**
    *
    * Decrypt a string
    *
    */
    public function decrypt( $text )
    {
        if ($text == '')
        {
            return '';
        }
        else
        {
            $c = base64_decode($text);
            $ivlen = $this->ivlen;
            $iv = substr($c, 0, $ivlen);
            $hmac = substr($c, $ivlen, $sha2len=32);
            $ciphertext_raw = substr($c, $ivlen+$sha2len);
            $original_text = openssl_decrypt($ciphertext_raw, $this->cipher, $this->key_2, $options=OPENSSL_RAW_DATA, $iv);

            $calcmac = hash_hmac($this->hash_hmac_type, $ciphertext_raw, strrev($this->key_1), $as_binary=true);
           
            if (hash_equals($hmac, $calcmac))
            {
                return $original_text;
            }    
            else
            {
                return '';
            }
        }
    }

    /**
    *
    * Hash a string
    *
    */
    public function hash( $text , $salt )
    {
        return hash_pbkdf2("sha512",$text.'|'.strrev($text),$salt,1000,0,false);
    }    
}
$crypt = new proCrypt;

// *** PW FILE CLASS *** \\
class pwFile extends proCrypt
{
    public $data = array();

    public function run($action)
    {
        if (!file_exists($this->data['file']))
        {
            exit('<p style="color:red">ERROR: Data not found</p>');
        }
        else
        {
            return $this->$action($this->data);
        }
    }

    private function get($data)
    {
        extract($data);
        return json_decode($this->decrypt(trim(file_get_contents($file))),true); 
    }

    private function update($data)
    {
        //print_r($data);exit();
        extract($data);
        $password = $this->hash($password,$pin);
        $passwords = json_decode($this->decrypt(file_get_contents($file)),true);
        $passwords[$id] = $password;
        file_put_contents($file,$this->encrypt(json_encode($passwords)));          
    }

    private function delete_entry($data)
    {
        //print_r($data);exit();
        extract($data);
        $passwords = json_decode($this->decrypt(file_get_contents($file)),true);
        unset($passwords[$id]);
        file_put_contents($file,$this->encrypt(json_encode($passwords)));  
    }
}
$pw_file = new pwFile;

// *** PW FILE FUNCTION SHORTCUTS *** \\
// *** GET ***
function pw_file_get($file)
{
    GLOBAL $pw_file;
    $pw_file->data = array('file' => $file);
    return $pw_file->run('get');
}
// *** UPDATE ***
function pw_file_update($file, $id, $password, $pin)
{
    GLOBAL $pw_file;
    $pw_file->data = array(
        'file'      => $file,
        'id'        => $id,
        'password'  => $password,
        'pin'       => $pin
    );
    $pw_file->run('update');
}
// *** DELETE PW FILE ENTRY ***
function pw_file_delete_entry($file, $id)
{
    GLOBAL $pw_file;
    $pw_file->data = array(
        'file'  => $file,
        'id'    => $id
    );
    $pw_file->run('delete_entry');
}
?>