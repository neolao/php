<?php
/**
 * @package Neolao\Auth
 */
namespace Neolao\Auth;

use \Neolao\Logger;

/**
 * Basic authentication with cookie
 */
class BasicCookie
{
    use \Neolao\Mixin\Singleton;
    use \Neolao\Mixin\GetterSetter;

    /**
     * The cookie key for the identity
     *
     * @var string
     */
    protected $_identityKey;

    /**
     * The cookie key for the session id
     *
     * @var string
     */
    protected $_sessionKey;

    /**
     * Current user instance
     *
     * @var mixed
     */
    protected $_currentUser;

    /**
     * The cookie path
     *
     * @var string
     * @see http://www.php.net/manual/en/function.setcookie.php
     */
    protected $_cookiePath;

    /**
     * The time the cookie expires (timestamp)
     *
     * @var int
     * @see http://www.php.net/manual/en/function.setcookie.php
     */
    protected $_cookieExpire;

    /**
     * The cookie domain
     *
     * @var string
     * @see http://www.php.net/manual/en/function.setcookie.php
     */
    protected $_cookieDomain;

    /**
     * Indicates that the cookie should only be transmitted over HTTPS
     *
     * @var bool
     * @see http://www.php.net/manual/en/function.setcookie.php
     */
    protected $_cookieSecure;

    /**
     * When TRUE the cookie will be made accessible only through the HTTP protocol
     *
     * @var bool
     * @see http://www.php.net/manual/en/function.setcookie.php
     */
    protected $_cookieHttpOnly;

    /**
     * Constructor
     */
    protected function __construct()
    {
        $this->_identityKey     = 'identity';
        $this->_sessionKey      = 'session';
        $this->_cookiePath      = '/';
        $this->_cookieExpire    = time() + 60 * 60 * 24 * 365; // 1 year
        $this->_cookieDomain    = null;
        $this->_cookieSecure    = false;
        $this->_cookieHttpOnly  = false;
    }

    /**
     * Get the identity of a user
     *
     * @param   mixed       $user           User instance
     * @return  string                      The identity
     */
    public function getIdentity($user)
    {
        // Override this
        return $user;
    }

    /**
     * Get the session id of a user
     *
     * @param   mixed       $user           User instance
     * @return  string                      The session id
     */
    public function getSessionId($user)
    {
        // Override this
        return sha1($user);
    }

    /**
     * Get a user instance by his identity
     *
     * @param   string      $identity       User identity
     * @return  mixed                       User instance
     */
    public function getUserByIdentity($identity)
    {
        // Override this
        return null;
    }

    /**
     * Set the current user with the session id
     *
     * @param   string      $identity       The identity
     * @param   string      $sessionId      The session id
     */
    public function setIdentity($identity, $sessionId)
    {
        setcookie($this->_identityKey, $identity, $this->_cookieExpire, $this->_cookiePath, $this->_cookieDomain, $this->_cookieSecure, $this->_cookieHttpOnly);
        setcookie($this->_sessionKey, $sessionId, $this->_cookieExpire, $this->_cookiePath, $this->_cookieDomain, $this->_cookieSecure, $this->_cookieHttpOnly);
    }

    /**
     * The current user instance
     *
     * @var mixed
     */
    protected function get_currentUser()
    {
        // If the current user is not set, then check the cookie
        try {
            if (is_null($this->_currentUser)) {
                if (isset($_COOKIE[$this->_identityKey]) && isset($_COOKIE[$this->_sessionKey])) {
                    $identity   = $_COOKIE[$this->_identityKey];
                    $sessionId  = $_COOKIE[$this->_sessionKey];

                    // Get the user instance and user session id
                    $user = $this->getUserByIdentity($identity);
                    $userSessionId = $this->getSessionId($user);

                    // Compare the user session id with the session cookie
                    if ($userSessionId === $sessionId) {
                        $this->currentUser = $user;
                    }
                }
            }
        } catch (Exception $exception) {
            $logger = Logger::getInstance();
            $logger->error($exception->getMessage());
            $logger->error($exception->getTraceAsString());
        }

        return $this->_currentUser;
    }
    protected function set_currentUser($user)
    {
        $this->_currentUser = $user;

        // Set the cookie
        if (!is_null($this->_currentUser)) {
            $identity   = $this->getIdentity($user);
            $sessionId  = $this->getSessionId($user);
            setcookie($this->_identityKey, $identity, $this->_cookieExpire, $this->_cookiePath, $this->_cookieDomain, $this->_cookieSecure, $this->_cookieHttpOnly);
            setcookie($this->_sessionKey, $sessionId, $this->_cookieExpire, $this->_cookiePath, $this->_cookieDomain, $this->_cookieSecure, $this->_cookieHttpOnly);
        } else {
            setcookie($this->_identityKey, '', -1, $this->_cookiePath);
            setcookie($this->_sessionKey, '', -1, $this->_cookiePath);
        }
    }
}
