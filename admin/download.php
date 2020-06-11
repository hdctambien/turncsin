<?php
session_start();
include '../lib/util.php';
include '../lib/util_admin.php';

$config = parse_ini_file("../config/config.ini");

// check if admin is logged in
adminCheck();

$slug = getAssignmentSlugOrRedirect();
$path = getAssignmentPath($slug);

//calcualte current time
$timezone = $config['timezone'];
$date = new DateTime("now", new DateTimeZone($timezone) );
$time = $date->format('YmdHis');

$filename = "${slug}-${time}.zip";

//zip contents of $path

$rootPath = realpath($path);

$zip = new ZipArchive();
$zip->open('downloads/'.$filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();

//redirect to admin page
//header("Location: ".url());

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='.$filename);
header('Content-Length: ' . filesize( 'downloads/'.$filename));
readfile('downloads/'.$filename);

// delete file after download
unlink('downloads/'.$filename);

exit();
