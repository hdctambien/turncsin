<?php
/** Identify the slug of the specified assignment from the GET data
 * Redirect the user to the admin home page if there is no slug, or
 * if the path to /assignments/slug does not exist
 */
function getAssignmentSlugOrRedirect()
{

  if(!isset($_GET['assignment']))
  {
    redirect();
  }

  $slug = $_GET['assignment'];

  if(false !== strpos($slug, "/") || false !== strpos($slug, "."))
  {
    redirect();
  }

  $path = getAssignmentPath($slug);
  if(!file_exists($path) || !is_dir($path))
  {
    redirect();
  }

  return $slug;
}

/** Identify the path to the specified slug
 *  @return String path to the specified slug
 */
function getAssignmentPath($slug)
{
  return  "../assignments/${slug}";
}

/** Write the specified data to the specified file in php ini format
 *
 *  Method from: https://stackoverflow.com/questions/5695145/how-to-read-and-write-to-an-ini-file-with-php
 *
 *  @param Array $array An associative array of data to write to ini file
 *  @param String $file The file name to write the ini data to
 */
function write_php_ini($array, $file)
{
    $res = array();
    foreach($array as $key => $val)
    {
        if(is_array($val))
        {
            $res[] = "[$key]";
            foreach($val as $skey => $sval) $res[] = "$skey = $sval";
        }
        else $res[] = "$key = $val";
    }
    safefilerewrite($file, implode("\r\n", $res));
}

/** Write the specified data to the specified file and avoid file locking
 *
 *  Method from: https://stackoverflow.com/questions/5695145/how-to-read-and-write-to-an-ini-file-with-php
 *
 *  @param String $filename Name of file to write to
 *  @param String $dataToSave Data to write to file
 */
function safefilerewrite($fileName, $dataToSave)
{
  // https://stackoverflow.com/questions/5695145/how-to-read-and-write-to-an-ini-file-with-php
   if ($fp = fopen($fileName, 'w'))
    {
        $startTime = microtime(TRUE);
        do
        {            $canWrite = flock($fp, LOCK_EX);
           // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
           if(!$canWrite) usleep(round(rand(0, 100)*1000));
        } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

        //file was locked so now we can store information
        if ($canWrite)
        {            fwrite($fp, $dataToSave);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }
}

 /** Recursively delete directory and all its files
  *
  * Method from: https://paulund.co.uk/php-delete-directory-and-files-in-directory
  *
  * @param String $target directory to delete
  */
function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

        foreach( $files as $file ){
            delete_files( $file );
        }

        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );
    }
}

/** Get a value from an associative array, or return $default if the specified key doesn't exist
 *  @param Array $arr The array to get a value from
 *  @param String $key The key to get from the array
 *  @param Object $default The value to return if $arr[$key] is not set
 *  @return Object The value stored in $arr at $key, or $default if $key is not set in $arr
 */
function getArrayValue($arr, $key, $default)
{
  return isset($arr[$key]) ? $arr[$key] : $default;
}

/** Identify how many students have turned in an assignment
 *  @param String $slug the folder name of the assignment to check
 *  @return int The number of students that have turned in the specified assignment
 */
function countTurnedInAssignments($slug)
{
  $files = scandir("../assignments/${slug}");
  // magic number 3 --> into.ini file and folders . and ..
  return count($files) - 3;
}

/** Get the names of all the students that have turned in the specified assignment
 *  @param String $slug the slug of the assignment to retrieve students from
 *  @return Array An array of student names that have turned something in for the specified assignment
 */
function getTurnedInAssignments($slug)
{
  $path = "../assignments/${slug}";
  $files = scandir($path);
  $students = [];

  foreach($files AS $file)
  {
    if('.' == $file || '..' == $file) continue;
    if(!is_dir($path."/${file}")) continue;

    array_push($students, $file);
  }

  return $students;
}

/** Get the names and timestamps of all the students that have turned in the specified assignment
 *  @param String $slug the slug of the assignment to retrieve students from
 *  @return Array An array of student names that have turned something in for the specified assignment
 */
function getTurnedInAssignmentsWithTimestamp($slug)
{
  $path = "../assignments/${slug}";
  $files = scandir($path);
  $students = [];

  foreach($files AS $file)
  {
    if('.' == $file || '..' == $file) continue;
    if(!is_dir($path."/${file}")) continue;

    //open log.txt
    //read last line
    $logPath = $path."/${file}/log.txt";
    $log = trim(file_get_contents($logPath));
    $log = explode("\n", $log);
    $log = implode(",", $log);


    array_push($students, [$file, $log]);
  }

  return $students;
}

/** Get assignment data from info.ini file
 *
 *  All data from info.ini will be retrieved for eaach assignment.
 *  Also, the assignment's slug will be retrieved.
 *  If there is no name stored in info.ini, then the slug will be used as the name
 *  @param Timezone $timezone The timezone to use when generating a timestamp
 *  @return Array An array of information about all assignments.
 */
function getAssignments()
{
  $base = '../assignments/';
  $folders = scandir($base);
  $assignments = [];

  foreach($folders AS $folder)
  {
    if($folder == '.' || $folder == '..') continue;
    if(!is_dir($base.$folder)) continue;

    $info = getAssignmentInfo($folder);

    //backup name incase the ini file is not configured correctly
    if(!isset($info['name']))
    {
      $info['name'] = $folder;
    }

    $info['slug'] = $folder;

    array_push($assignments, $info);
  }

  return $assignments;
}

/** Get data about the specified assignment from its info.ini file
 *  @param String $slug The slug of the assignment to get data from
 *  @return Array An associative array with all the data from the specified assignments info.ini file
 */
function getAssignmentInfo($slug)
{
  $path = "../assignments/${slug}/info.ini";
  if(!file_exists($path)) return [];
  return parse_ini_file($path);
}
