<?php 
/* 
#########################################################
This module is Copyright (c) 2007 Worldwide Webdesign Ltd  
and may not be reproduced without written consent
#########################################################
*/
?>
<?php
function metatags($query)
{  
  $result = database($query);
  extract($result);
  if (!$pagetitle) {$pagetitle = $title;}
  if (!$metadescription) {$metadescription = $description;}
  if ($pagetitle == '') {$pagetitle = BUSINESS;}
  
  $metatags = '<title>'.$pagetitle.'</title>'."\n";
  if ($keywords > '') {$metatags .= '<meta name="keywords" content="'.$keywords.'" />'."\n";}
  $metatags .= '<meta name="description" content="'.$metadescription.'" />'."\n";
  return $metatags;
}

function textblock($textid)
{  
  $result = database("SELECT `text` FROM `text` WHERE textid='$textid' LIMIT 1");
  $textblock = $result['text'];

  $textblock = '<div id="text_block" class="textblock'.$textid.'">'.$textblock.'</div>';

  $textblock = textblock_img($textblock);
  $textblock = textblock_smarturls($textblock);
  
  return $textblock;
}

function textblock_img($textblock)
{
  if ($_SERVER['HTTP_HOST'] == 'localhost')
  {
    $textblock = str_replace("../uploads/", FULLDOMAIN.SERVER_PATH."uploads/", $textblock);
  }
  else
  {
    $textblock = str_replace("../uploads/", "uploads/", $textblock); 
  }
  return $textblock;
}

function textblock_smarturls($textblock) 
{
  $findme_start = '[SM-URL:';
  $findme_end = ']';

  while(strpos($textblock, $findme_start) != false)
  {
    $pos_start = strpos($textblock, $findme_start);
    $pos_end = strpos($textblock, $findme_end, $pos_start);
    $sub_str = substr($textblock,($pos_start+1),(($pos_end-$pos_start)-1));

    //echo $pos_start.'-'.$pos_end.'-'.$sub_str;

    $urlid = str_replace('SM-URL:', '', $sub_str);
    $find_str = $findme_start.$urlid.$findme_end;

    $url_result = database("SELECT * FROM smarturls WHERE urlid='$urlid' LIMIT 1");

    $textblock = str_replace($find_str, $url_result['url'], $textblock);
  }

  return $textblock;
}

function spamcheck($_strings)
{
  if (!is_array($_strings)) {echo '<p>strings must be inside an array</p>'; exit();}
  $showechos = 'N';
  $_spam = 'no';

  // SETTINGS \\
  $checks = array(
    'string' => array(
      'run_check' => 'no',
      'run_option' => 'both', // both / min / max \\
      'min_length' => 20,
      'max_length' => 500
    ),
    'http_https' => array(
      'run_check' => 'yes'
    ),
    'character' => array(
      'run_check' => 'yes',
      'characters' =>  array('â','€','¢'),
      'max_characters' => 1
    ),
    'diff_cases' => array(
      'run_check' => 'yes',
      'case_array' => array(
        'UUL','UULU','ULUL','ULLU','LUL'
      )
    )
  );
  // END SETTINGS \\

  $count = 0;
  foreach ($_strings as $key => $string) 
  {
    $count++;
    $stringlength = strlen($string); 

    foreach ($checks as $check_type => $check) 
    {
      if ($showechos == 'Y') {echo '<p>'.$count.'</p>';}

      foreach ($check as $variable => $value) {${$variable} = $value;}

      switch ($check_type) 
      {
        case 'string':
          // STRING LENGTH \\
          if ($run_check <> 'no')
          {
            if ($stringlength < $min_length && ($run_option == 'min' or $run_option == 'both')) {$_spam='yes';}
            if ($stringlength > $maxstringlength && ($run_option == 'max' or $run_option == 'both')) {$_spam='yes';}
          }
          break;
        case 'http_https':
          // HTTP & HTTPS \\
          if ($run_check == 'yes')
          {
            $position = strpos($string, 'ttp://'); // Must be 'ttp://' 
            if ($position === false) {$position = strpos($string, 'ttp:');}
            if ($position > '') {$_spam='yes';}

            $position = strpos($string, 'ttps://'); // Must be 'ttps://' 
            if ($position === false) {$position = strpos($string, 'ttps:');}
            if ($position > '') {$_spam='yes';}
          }
          break;
        case 'character':
          // SPECIAL CHARACTERS \\
          if ($run_check == 'yes')
          {
            $findmearray = $characters;
            $special = 0;
            foreach ($findmearray as $findme) 
            {
              $position = strpos($string, $findme);
              if ($position > -1) {$special += substr_count($string, $findme);}
              //echo '<p>'.$findme.'-'.$position.'-'.$special.'</p>';
              if ($special > 1000) {exit();}
            }
            if ($special >= $max_characters) {$_spam='yes';}
          }
          break;
        case 'diff_cases':
          // DIFFERENT CAESES IN STRING \\
          if ($run_check == 'yes')
          {
            $substrings = explode(' ', $string);
            foreach ($substrings as $substring) 
            {
              $substringlength = strlen($substring); 

              $i = 0; $case = array();
              while ($i <= $substringlength)
              {
                $letter = substr($substring, $i, 1);
                if (ctype_lower($letter)) {$case[$i] = 'L';}
                if (ctype_upper($letter)) {$case[$i] = 'U';}
                $i++;
              }
              $casestr = implode("", $case);
              //echo '<p>'.$substring.': '.$casestr.'</p>';
              foreach ($case_array as $findcase) 
              {
                $position = strpos($casestr, $findcase);
              }
              if ($position > '') {$_spam='yes';}
            }          
          }
          break;
      }
      if ($_spam=='yes') { return $_spam; }
      foreach ($check as $variable => $value) {unset(${$variable});}
    }
  }
  return $_spam;
}

function slider($data) 
{
  extract($data);

  $number_of_slides = ($split_text == true?count($slides)*2:count($slides));

  if (!is_integer($number_of_slides / $slides_visible) && $limit_to_multiples == true)
  {
    $slider_dec = explode('.', ($number_of_slides / $slides_visible)); // Round function \\
    $number_of_slides = $slider_dec[0] * $slides_visible;
  }

  $slider_js_options['minSlides'] = $slides_visible - 1;
  $slider_js_options['maxSlides'] = $slides_visible;

  if ($slides_visible == 1)
  {
    $slider_width = 100 * ($number_of_slides + 2);
    $slide_width = ($number_of_slides + 2);
  }
  else
  {
    $slider_width = 100 * (($number_of_slides + ($slides_visible * 2)) / $slides_visible);
    $slide_width =  ($number_of_slides + ($slides_visible * 2));
    //$slider_js_options['pager'] = false;
  }

  // FIX TO ID ONLY \\
  if (isset($slider_js_options['pagerCustom'])) {$slider_js_options['pagerCustom'] = '#'.$pagerCustom['id'] = $slider_js_options['pagerCustom'];}

  //echo '<p>'.$number_of_slides.' - '.$slider_width.'% - 100% / '.$slide_width.'</p>';
  if (isset($imagesslider['before_html'])) {echo $imagesslider['before_html'];}
  ?>
  <div class="imagesslider" data-settings='<?php echo json_encode($slider_js_options); ?>' <?php if ($popup == true) {echo 'data-popup="true"';} ?> style="width: <?php echo $slider_width; ?>%!important;">
  <?php
  foreach ($slides as $slider_array_id => $slide) 
  {
    if (($slider_array_id+1) > $number_of_slides) {break;}
    foreach ($slide as $key => $value) {${$key} = $value;}

    switch ($slider_type) {
      case 'album':
        $img = $image_path.'/images/'.$image;
        $slidetext_class = "slidetext albums";
        if ($title > '')  {$title_html = '<h2>'.$title.'</h2>'."\n";} else {$title_html = '';}
        if ($text > '')   {$text_html = '<p>'.$text.'</p>'."\n";} else {$text_html = '';}
        if ($url > '')    {$url_html = '<a class="button" href="'.$url.'">Read More</a>'."\n";} else {$url_html = '';}
        break;
      case 'gallery':
        $img = $image_path.'/images/'.$image;
        break;
      case 'testimonials':
        $slidetext_class = 'testimonials';
        if ($description > '') 
        {
          $text_html = '<blockquote>';
            $text_html .= $description;
            $text_html .= '<cite>&ndash;'.$name.'</cite>';
          $text_html .= '</blockquote>';
        }
        break;
      default:
        $img = $image_path.$id.(file_exists($image_path.$id.'/images/thumbs/'.$image)?'/images/thumbs/':'/images/').$image;
        $slidetext_class = "slidetext ".$show_text;
        if ($title > '')  {$title_html = '<a class="button" href="'.$url.'"><h2>'.$title.'</h2></a>'."\n";} else {$title_html = '';}
        if ($text > '')   {$text_html = '<a class="button" href="'.$url.'"><p>'.$text.'</p></a>'."\n";} else {$text_html = '';}
        break;
    }
    ?>
    <div class="slide" style="width: calc(100%<?php echo '/'.$slide_width; ?>)!important;">
      <?php if ($popup == true) { ?>
        <a href="<?php echo $img; ?>">
          <img src="<?php echo $img; ?>" />
        </a>
      <?php } elseif ($img > '') { ?>
        <?php if ($split_text == true) { ?>
          <img href="<?php echo $img; ?>" src="<?php echo $img; ?>" />
        <?php } else { ?>
          <img src="<?php echo $img; ?>" />
        <?php } ?>
      <?php } ?>
      <?php if ($split_text <> true && strlen($title_html.$text_html.$url_html) > 0) { ?>
        <div class="<?php echo $slidetext_class; ?>">
          <?php 
            if ($title_html)  {echo $title_html;}
            if ($text_html)   {echo $text_html;}
            if ($url_html)    {echo $url_html;} 
          ?>
        </div>
      <?php } ?>
    </div>
    <?php if ($split_text == true) { ?>
      <div class="slide" style="width: calc(100%<?php echo '/'.$slide_width; ?>)!important;">
        <div>
        <?php 
          if ($title_html)  {echo $title_html;}
          if ($text_html)   {echo $text_html;}
          if ($url_html)    {echo $url_html;} 
        ?>
        </div>
      </div>
    <?php
    }
  }
  ?>
  </div>
  <?php  
  if (isset($imagesslider['after_html'])) {echo $imagesslider['after_html'];}

  if (isset($pagerCustom))
  {
    if (isset($pagerCustom['before_html'])) {echo $pagerCustom['before_html'];}

    echo '<div id="'.$pagerCustom['id'].'">';
    foreach ($slides as $slider_array_id => $slide) 
    {
      if (($slider_array_id+1) > $number_of_slides) {break;}
      foreach ($slide as $key => $value) {${$key} = $value;}

      echo '<a class="'.$pagerCustom['class'].'" data-slide-index="'.$slider_array_id.'">';
        echo '<img src="'.$image_path.(file_exists(str_replace('http://cmsplus.bpweb.net/', '', $image_path).'/image/thumbs/'.$image)?'/image/thumbs/':'/image/').$image.'" />';
      echo '</a>';
    }
    echo '</div>';

    if (isset($pagerCustom['after_html'])) {echo $pagerCustom['after_html'];}
  }
}

function module($module)
{  
  $result = database("SELECT status FROM modules WHERE module='$module' LIMIT 1");
  return $result['status'];
}

function email_template($body)
{
  ob_start();
    include SERVER.'css/emails.css';
  $email_css .= ob_get_clean();

  $emailbody = '<html>'."\n".
    '<head>'."\n".
    '<title>'.$title.'</title>'."\n".
    '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'."\n".
    '<style type="text/css">'."\n".$email_css."\n".'</style>'."\n".
    '</head>'."\n".
    '<body bgcolor="#FFFFFF">'."\n".$body."\n".'</body>'."\n".
    '</html>';
    
  return $emailbody;
}

// *** INCULDED FUNCTIONS FROM FOLDER *** \\
//include_once SERVER.'admin/includes/functions/shared.php';
//include_once SERVER.'admin/includes/functions/shipping.php';
//include_once SERVER.'admin/includes/functions/shop.php';

function table_data($id_ref,$return_data)
{
    if ($return_data == 'table_id')
    {
        $result = database("SELECT * FROM tables_single WHERE table_number='$id_ref' LIMIT 1");
    }
    else
    {
        $result = database("SELECT * FROM tables_single WHERE table_id='$id_ref' LIMIT 1");
    }
    return $result[$return_data];
}
?>