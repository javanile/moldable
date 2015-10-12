<?php

##
echo '<h1>Tests</h1>';

##
foreach(scandir('.') as $folder) {

	##
	if (!is_dir($folder) || $folder[0] == '.') {
		continue;
	}

	##
	echo '<h3>'.$folder.'</h3><ul>';
	
	##
	foreach(scandir($folder) as $file) {
		
		if (is_dir($folder.'/'.$file) || $file[0] == '.' || $file == 'common.php') {
			continue;
		}
		
		echo '<li><a href="'.$folder.'/'.$file.'" target="_blank">'.$file.'</a></li>';
	}
	
	echo '</ul>';
}