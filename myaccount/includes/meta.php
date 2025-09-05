<title><?php echo $pagetitle; ?></title>
<?php if($metadescription != '') { ?>
<meta name="description" content="<?php echo $metadescription; ?>" />
<?php } ?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta name="format-detection" content="telephone=no" />
<?php if ($pageurl <> '404.php') { ?>
	<link rel="canonical" href="<?php echo FULLDOMAIN.$canonicalurl;?>" />
<?php } ?>
<meta name="robots" content="<?php if($index_follow <> '') {echo $index_follow;} else {echo'index,follow';} ?>" />