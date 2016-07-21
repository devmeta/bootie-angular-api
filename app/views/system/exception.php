<?php echo get_class($exception); ?>
<?php echo $exception->getMessage(); die(); ?>


	<?php
	$x = FALSE;
	if($backtrace = $exception->getTrace())
	{
		foreach($backtrace as $id => $line)
		{
			if(!isset($line['file'],$line['line']))continue;

			$x = TRUE;

			print '<div class="box">';

			//Skip the first element
			if( $id !== 0 )
			{
				// If this is a class include the class name
				print '<b>Called by '. (isset($line['class']) ? $line['class']. $line['type'] : '');
				print $line['function']. '()</b>';
			}

			// Print file, line, and source
			print ' in '. $line['file']. ' ['. $line['line']. ']';
			print '<code class="source">'. \bootie\Error::source($line['file'], $line['line']). '</code>';

			if(isset($line['args']))
			{
				print '<b>Function Arguments</b>';
				print dump($line['args']);
			}

			print '</div>';
		}

	}

	if(!$x)
	{
		print '<p><b>'.$exception->getFile().'</b> ('.$exception->getLine().')</p>';
	}
	?>

