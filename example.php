<?php
session_start();

include('nocsrf.php');

if ( isset( $_POST[ 'field' ] ) )
{
    try
    {
        // Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
        NoCSRF::check( 'csrf_token', $_POST, true, 60*10, false );
        // form parsing, DB inserts, etc.
        // ...
        $result = 'CSRF check passed. Form parsed.';
    }
    catch ( Exception $e )
    {
        // CSRF attack detected
        $result = $e->getMessage() . ' Form ignored.';
    }
}
else
{
    $result = 'No post data yet.';
}
// Generate CSRF token to use in form hidden field
$token = NoCSRF::generate( 'csrf_token' );
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CSRF Vulnerability</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
</head>
<body>

    <section class="container">
        <h1 class="title is-1">CSRF Vulnerability Example</h1>
        <article class="message is-warning">
            <div class="message-header">
              <p>Warning</p>
              <button class="delete" aria-label="delete"></button>
            </div>
            <div class="message-body">
                <?php echo $result; ?>
            </div>
          </article>
        <form name="csrf_form" action="#" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
            <div class="field">
                <label class="label">Form With Anti CSRF Token.</label>
                <div class="control">
                  <input class="input" type="text" name="field" placeholder="Text input">
                </div>
              </div>
              
              <div class="control">
                <button class="button is-primary">Submit</button>
              </div>
              </input>
        </form>
        <br/>
        <article class="message is-info">
            <div class="message-header">
              <p>Token</p>
              <button class="delete" aria-label="delete"></button>
            </div>
            <div class="message-body">
                <?php echo $token; ?>
            </div>
          </article>


        <br/><br/>
        <form name="nocsrf_form" action="#" method="post">
            <input type="hidden" name="csrf_token" value="whateverkey">
            <div class="field">
                <label class="label">Form Without Anti CSRF Token.</label>
                <div class="control">
                  <input class="input" type="text" name="field" placeholder="Text input">
                </div>
              </div>
              <div class="control">
                <button class="button is-primary">Submit</button>
              </div>
        </form>
</section>
</body>
</html>

