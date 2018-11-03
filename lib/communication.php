<?php
/**
 * Sends an email to an email address.
 * Uses the mail function which seeming returns true on everything.
 */
function communication_send_email(string $to, string $from,
                                    string $subject, string $message)
{
	$headers = "From: ".$from;
	if(!mail($to, $subject, $message, $headers))
		return 0;
	
	return 1;
}