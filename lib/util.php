<?php
/** Identify the current page's url
 *  @return String the url to the current page
 */
function url(){

  $uri = $_SERVER['REQUEST_URI'];

  //Remove last token of URI (the x.php part)
  if(-1 != strpos($uri, '.php'))
  {
    $index = strrpos($uri, "/");
    $uri = substr($uri, 0, $index);
  }

  if(isset($_SERVER['HTTPS'])){
      $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
  }
  else{
      $protocol = 'http';
  }
  return $protocol . "://" . $_SERVER['HTTP_HOST'] . $uri;
}

/** Redirect the user to the specified path
 *  @param String $path The uri to redirect the user to
 */
function redirect($path='')
{
  header('Location: '.url().'/'.$path);
  exit();
}

/** Redirect the user to the homepage if they are not logged in as an admin
 */
function adminCheck()
{
  if(!isAdmin())
  {
    $url = url();
    $url = str_replace('admin', '', $url);
    header('Location: '.$url.'/'.$path);
    exit();
  }
}

/** Identify if the current user is logged in
 *  @return boolean true if user is logged in
 */
function isLoggedIn()
{
  return isset($_SESSION["logged_in"]);
}

/** Identify if the current user is an admin
 *  @return boolean true if user is logged in as an admin
 */
function isAdmin()
{
  if(isset($_SESSION["logged_in"]))
  {
      return $_SESSION["logged_in"] === "admin";
  }
  return false;
}
