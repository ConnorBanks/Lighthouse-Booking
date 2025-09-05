<?php
class php_config_define_manipulation {
    public $config_file_path;

    public function __construct($file_path,$section='') {
      $this->config_file_path = $file_path;
      $this->section = $section;
    }

    public function get($line_has)
    {
      $constant = $line_has;
      $match_line = array();
      $config_file_path = $this->config_file_path;
      $config_file = fopen($config_file_path, "r");
      if($config_file)
      {
         //Output a line of the file until the end is reached
         $i = 0;
         while(!feof($config_file))
         {
            $i++;
            $config_old_line = fgets($config_file);
            $pos = strpos($config_old_line, $constant);
            if( $pos !== false )
            {
               $match_lines[] = $config_old_line;
            }
         }
         fclose($config_file);
         return $match_lines;
      }else{
         throw new Exception('Unable to open file!');
      }
    }

  	public function update($form_config_arr)
  	{
      //echo '<pre>'.print_r($form_config_arr,true).'</pre>';
      $config_file_path = $this->config_file_path;
  	  if( (is_readable($config_file_path)) && is_writable($config_file_path))
  	  {
  	     if(!$config_old_file_content = file_get_contents($config_file_path))
  	     {
  	        throw new Exception('Unable to open file!');
  	     }

  	     $config_old_arr = array();
  	     $config_new_arr = array();

  	     foreach ($form_config_arr as $constant => $value){
            //echo '<p>'.$constant.' = '.$value.'</p>';
  	        $config_old_line = $this->getLine($constant);
  	        $config_old_arr[] = $config_old_line;

            $config_new_arr[] = "define('$constant', '$value');\n";
  	     }
         //echo '<pre>'.print_r($config_old_arr,true).'</pre>';
         //echo '<pre>'.print_r($config_new_arr,true).'</pre>';

         //exit();
  	     $config_new_file_content = str_replace($config_old_arr, $config_new_arr, $config_old_file_content);

  	     $new_content_file_write = file_put_contents($config_file_path, $config_new_file_content);

  	     foreach ($config_new_arr as $constant => $value)
  	     {
  	        //echo $value.'<br/>';
  	     }
  	     return true;
  	  }else{
  	     throw new Exception('Access denied for '.$config_file_path);
  	     return false;
  	  }

  	}

    public function getLine($constant)
    {
      $match_line = '';
      $config_file_path = $this->config_file_path;
      $config_file = fopen($config_file_path, "r");
      if($config_file)
      {
         //Output a line of the file until the end is reached
         $i = 0;
         while(!feof($config_file))
         {
            $i++;
            $config_old_line = fgets($config_file);
            $pos = strpos($config_old_line, $constant);
            if( $pos !== false )
            {
               $match_line= $config_old_line;
               //echo '<p>'.$constant.' - '.$match_line.'</p>';
               break;
            }
         }
         fclose($config_file);
         return $match_line;
      }else{
         throw new Exception('Unable to open file!');
      }
    }

    public function getAdvanced() {
      $defines = array();
      $state = 0;
      $key = '';
      $value = '';

      $file = file_get_contents($this->config_file_path);
      $tokens = token_get_all($file);

      if ($this->section > '')
      { 
        foreach ($tokens as $token_id => $token) 
        {
          //echo '<p>'.$token[1].' - // '.$this->section.' \\\\</p>';
          if (trim($token[1]) <> trim('// '.$this->section.' \\\\')) 
          {
            unset($tokens[$token_id]);
          } 
          else
          {
            break;
          }
        }
      }
      //echo '<pre>'.print_r($tokens,true).'</pre>';
      
      $token = reset($tokens);
      while ($token) {
          
          //return $this->dump($state, $token);
          if (is_array($token)) {
              if ($token[0] == T_WHITESPACE || $token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
                  // do nothing
              } else if ($token[0] == T_STRING && strtolower($token[1]) == 'define') {
                  $state = 1;
              } else if ($state == 2 && $this->is_constant($token[0])) {
                  $key = $token[1];
                  $state = 3;
              } else if ($state == 4 && $this->is_constant($token[0])) {
                  $value = $token[1];
                  $state = 5;
              } 
          } else {
              $symbol = trim($token);
              if ($symbol == '(' && $state == 1) {
                  $state = 2;
              } else if ($symbol == ',' && $state == 3) {
                  $state = 4;
              } else if ($symbol == ')' && $state == 5) {
                  $defines[$this->strip($key)] = $this->strip($value);
                  $state = 0;
              }
          }
          $token = next($tokens);
          //echo '<p>Token: <br />'.$token[1].'</p>'; 
          if ($this->section > '' && trim($token[1]) == trim('// END '.$this->section.' \\\\')) {
            break;
          }      
      }

      $i = 0;
      foreach ($defines as $k => $v) {
          //$return[] = array('name' => $k, 'value' => $v);
          $return[$i]['name'] = $k;
          $return[$i]['value'] = $v;
          $i++;
      }
      return $return;
    }

    private function is_constant($token) {
        return $token == T_CONSTANT_ENCAPSED_STRING || $token == T_STRING ||
            $token == T_LNUMBER || $token == T_DNUMBER;
    }

    private function dump($state, $token) {
        if (is_array($token)) {
            echo "$state: " . token_name($token[0]) . " [$token[1]] on line $token[2]\n";
        } else {
            echo "$state: Symbol '$token'\n";
        }
    }

    private function strip($value) {
        return preg_replace('!^([\'"])(.*)\1$!', '$2', $value);
    }
}