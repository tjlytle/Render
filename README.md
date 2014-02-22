Render
======
A *very* simple rendered for PHP based templates.  **Not** a template language, just a simple wrapper that handles
undefined template variables, helper functions, and turing a `phtml` file into a string.

If you're using some kind of framework, you probably shouldn't be using this. I threw this together to be pasted (yes,
pasted, if you have composer up and running, pull in a real template library) into exsisting legacy projects and turn
classic HTML with embedded PHP into a bit more up-to-date PHP rendered templates.

Features
--------
- Set View Variables
- Set View Helpers
- Render Template in Layout

Example
-------
index.php:

    $render = new Render('layout.phtml');
    $render->name = 'Tim';
    $render->message = 'This is a secret message';
    $render->encode = function($value){
        return str_rot13($value);
    };

    echo $render('template.phtml');

layout.phtml:

    <html>
      <head>Example Layout</head>
      <body>
        <h1>My Old Site</h1>
        <div><?php echo $this->content; ?></div>
      </body>
    </html>

template.phtml

    <p>Hi <?php echo $this->name ?>, welcome back.</p>
    <p>Here's your secret message: <?php echo $this->encode($this->message); ?></p>
