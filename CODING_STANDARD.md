# Coding standard - PHP
This coding standard is based on the PSR.


## Files

* Files use only UTF-8 without BOM.
* Files use the Unix LF (linefeed) line ending.
* Files start with <?php
* The closing ?> tag is omitted from files containing only PHP.
* Each _ character in the class name is converted to a DIRECTORY_SEPARATOR. The _ character has no special meaning in the namespace.
* The fully-qualified namespace and class is suffixed with .php when loading from the file system.
* Code uses 4 spaces for indenting, not tabs.
* There is no limit on line length.
* PHP keywords are in lower case.

### Example
| Class name      | File path           |
| ----------------| ------------------- |
| BO_Mail_Message | BO/Mail/Message.php |


## Classes

* Alphabetic characters in vendor names, namespaces, and class names may be of any combination of lower case and upper case.
* When present, there is one blank line after the namespace declaration.
* When present, all use declarations go after the namespace declaration.
* There is one use keyword per declaration.
* There is one blank line after the use block.
* Class names are declared in StudlyCaps.
* The extends and implements keywords are declared on the same line as the class name.
* Opening braces for classes go on the next line, and closing braces go on the next line after the body.
* Visibility is declared on all properties and methods; abstract and final are declared before the visibility; static is declared after the visibility.

### Constants

* Class constants are declared in all upper case with underscore separators.

### Methods

* Method names are declared in camelCase.
* Method names are not declared with a space after the method name. There is no space after the opening parenthesis, and there is no space before the closing parenthesis.
* Opening braces for methods go on the next line, and closing braces go on the next line after the body.
* In the argument list, there is no space before each comma, and there is one space after each comma.
* Method arguments with default values go at the end of the argument list.

### Properties

* Properties are written in camelCase.

### Comments

* Comments are written in [JavaDoc](http://en.wikipedia.org/wiki/Javadoc) format.


### Control structures

* There is one space after the control structure keyword
* There is no space after the opening parenthesis
* There is no space before the closing parenthesis
* There is one space between the closing parenthesis and the opening brace
* The structure body is indented once
* The closing brace is on the next line after the body
* The body of each structure is enclosed by braces. This standardizes how the structures look, and reduces the likelihood of introducing errors as new lines get added to the body.
* Opening braces for control structures go on the same line, and closing braces go on the next line after the body.
* When making a method or function call, there is no space between the method or function name and the opening parenthesis, there is no space after the opening parenthesis, and there is no space before the closing parenthesis. In the argument list, there is no space before each comma, and there is one space after each comma.
* Argument lists may be split across multiple lines, where each subsequent line is indented once. When doing so, the first item in the list is on the next line, and there is only one argument per line.

### Examples

```php
<?php
/**
 * Description
 */
class BO_Mail_Message extends BO_Mail_Abstract implements Serializable
{
    const MY_CONSTANT = 'hello';

    /**
     * Email
     * 
     * @var string
     */
    public $email = null;

    /**
     * Get the email
     * 
     * @return string        The email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Do something
     * 
     * @param string     $a     First parameter
     * @param int        $b     Second parameter
     */
    final public static function doSomething($a, b = null)
    {
        if ($a === $b) {
            bar();
        } elseif ($a > $b) {
            $foo->bar($arg1);
        } else {
            BazClass::bar($arg2, $arg3);
        }
    }
}
```

```php
<?php
/**
 * @package BO\Mail
 */
namespace BO\Mail;

use Util\String;
use Util\FileSystem as FS;

/**
 * Description
 */
class Foo extends Bar implements Serializable
{
    /**
     * Do something
     */
    protected function doSomething()
    {
        $foo->bar(
            $arg1,
            $arg2
        );

        switch ($arg3) {
            case 0:
                // Comment
                break;
            default:
                break;
        }

        for ($i = 0; $i < 10; $i++) {
            // for body
        }

        foreach ($array as $key => $value) {
        }
    }
}
```

