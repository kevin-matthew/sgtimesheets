<?php
define('SGTS_MAXFILESIZE', 1 * 1000000 * 25); //25 megabytes
define('SGTS_ALLOWEDTYPE', array('pdf' => 'application/pdf'
, 'xls' => 'application/vnd.ms-excel'
, 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
, 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
, 'doc'  => 'application/msword'
, 'bin' => 'application/octet-stream'));
define('SGTS_UPLOADDEST' , $_SERVER['DOCUMENT_ROOT'] . '/../attachment_uploads');

/*
 * This function reads from $_FILES and uploads the first file and returns the path of where the file was stored.
 * file_upload_name: the name specified in the HTML's <input> tag
 * on failure, 0 is returned and err_str is set to user-readable error.
 */
function uploadattachment(string $file_upload_name, string &$err_str = '')
{
	$tmpfile = @$_FILES[$file_upload_name];
	print_r($_FILES);
	if(!$tmpfile)
	{
		$err_str = 'No file upload attempted';
		return 0;
	}
	
	// Check to see if there was any errors with upload
	switch($tmpfile['error'])
	{
	case UPLOAD_ERR_OK: break;
	case UPLOAD_ERR_NO_FILE:
		$err_str = 'No file included in upload';
		return 0;
	case UPLOAD_ERR_INI_SIZE:
	case UPLOAD_ERR_FORM_SIZE:
		$err_str = 'Exceeded filesize limit';
		return 0;
	default:
		$err_str = 'Unknown upload error';
		return 0;
	}
	if($tmpfile['size'] > SGTS_MAXFILESIZE)
	{
		$err_str = 'Filesize too large';
		return 0;
	}

	// make sure the file is off allowed type.
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$mime  = finfo_file($finfo, $tmpfile['tmp_name']);
	finfo_close($finfo);
	if(false === $ext = array_search($mime, SGTS_ALLOWEDTYPE))
	{
		$err_str = 'File type not allowed, allowed file types are: '
			. implode(', ', array_keys(SGTS_ALLOWEDTYPE));
		return 0;
	}
	// Sometimes the MIME type will default to octet-stream even
	// though its a zip file/xlsx. So we just say fuck it and append
	// the user specified extension. Only if it's an okay extension,
	// of course.  Furthermore we'll check to make sure the extension
	// is in the allowed types.
	if($ext == 'bin')
	{
		if(preg_match('/\.([a-zA-Z0-9]+)$/'
		, $tmpfile['name']
		, $matches)
		&& array_search($matches[1], array_keys(SGTS_ALLOWEDTYPE)))
			$ext = $matches[1];
		else
		{
			$err_str = 'File type not allowed';
			return 0;
		}
	}

	// preform the actual upload.
	// name it uniquely (as in sha1 in this case)
	$filedest = SGTS_UPLOADDEST . '/' . sha1_file($tmpfile['tmp_name']) . '.' . $ext;
	if(!move_uploaded_file($tmpfile['tmp_name'], $filedest))
	{
		$err_str = 'Server failed to upload file';
		return 0;
	}

	// At this point, the file was uploaded just nicely.
	return $filedest;
}
